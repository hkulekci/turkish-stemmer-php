<?php
namespace TurkishStemmer;

use TurkishStemmer\State\DerivationalStateFactory;
use TurkishStemmer\State\NominalVerbStateFactory;
use TurkishStemmer\State\NounStateFactory;
use TurkishStemmer\State\StateInterface;
use TurkishStemmer\Suffix\DerivationalSuffixFactory;
use TurkishStemmer\Suffix\NominalVerbSuffixFactory;
use TurkishStemmer\Suffix\NounSuffixFactory;
use TurkishStemmer\Suffix\Suffix;

class Stemmer
{
    protected $protectedWords = [];
    protected $vowelHarmonyExceptions = [];
    protected $lastConsonantExceptions = [];
    protected $averageStemSizeExceptions = [];

    protected $container;

    /**
     * Stemmer constructor.
     *
     * @param array $protectedWords
     * @param array $vowelHarmonyExceptions
     * @param array $lastConsonantExceptions
     * @param array $averageStemSizeExceptions
     */
    public function __construct(
        array $protectedWords = null,
        array $vowelHarmonyExceptions = null,
        array $lastConsonantExceptions = null,
        array $averageStemSizeExceptions = null
    ) {
        if ($protectedWords === null) {
            $this->protectedWords = Helper::loadWordSet(Helper::DEFAULT_PROTECTED_WORDS_FILE);
        } else {
            $this->protectedWords = $protectedWords;
        }

        if ($vowelHarmonyExceptions === null) {
            $this->vowelHarmonyExceptions = Helper::loadWordSet(Helper::DEFAULT_VOWEL_HARMONY_EXCEPTIONS_FILE);
        } else {
            $this->vowelHarmonyExceptions = $vowelHarmonyExceptions;
        }

        if ($lastConsonantExceptions === null) {
            $this->lastConsonantExceptions = Helper::loadWordSet(Helper::DEFAULT_LAST_CONSONANT_EXCEPTIONS_FILE);
        } else {
            $this->lastConsonantExceptions = $lastConsonantExceptions;
        }

        if ($averageStemSizeExceptions === null) {
            $this->averageStemSizeExceptions = Helper::loadWordSet(Helper::DEFAULT_AVERAGE_STEM_SIZE_EXCEPTIONS_FILE);
        } else {
            $this->averageStemSizeExceptions = $averageStemSizeExceptions;
        }

        $this->container = new Container();

        $this->container->set(DerivationalSuffixFactory::class, new DerivationalSuffixFactory($this->container));
        $this->container->set(NominalVerbSuffixFactory::class, new NominalVerbSuffixFactory($this->container));
        $this->container->set(NounSuffixFactory::class, new NounSuffixFactory($this->container));

        $this->container->set(DerivationalStateFactory::class, new DerivationalStateFactory($this->container));
        $this->container->set(NominalVerbStateFactory::class, new NominalVerbStateFactory($this->container));
        $this->container->set(NounStateFactory::class, new NounStateFactory($this->container));
    }

    /**
     * Checks whether a stem process should proceed or not.
     * @param string $word
     * @return bool
     */
    private function proceedToStem(string $word): bool
    {
        if (!$word) {
            return false;
        }

        if (!Helper::isTurkish($word)) {
            return false;
        }

        if ($this->protectedWords && \in_array(Helper::toLower($word), $this->protectedWords, true)) {
            return false;
        }

        if (Helper::countSyllables($word) < 2) {
            return false;
        }

        return true;
    }

    /**
     * Finds the stem of a given word.
     * @param string $word
     * @return string
     */
    public function stem(string $word): string
    {
        if (!$this->proceedToStem($word)) {
            return $word;
        }

        $stems = [];
        # Process the word with the nominal verb suffix state machine.
        $stems = $this->nominalVerbSuffixStripper($word, $stems);
        $stems[] = $word;

        $nounStems = [];
        foreach ($stems as $stem) {
            $nounStems = array_unique(array_merge($this->nounSuffixStripper($stem, $stems), $nounStems));
        }

        $stems = $nounStems;
        $stems[] = $word;
        $stems = array_unique($stems);

        $derivationalStems = [];
        foreach ($stems as $stem) {
            $derivationalStems = array_unique(array_merge($this->derivationalSuffixStripper($stem, $stems), $derivationalStems));
        }
        $stems = $derivationalStems;

        return $this->postProcess($stems, $word);
    }

    /**
     * It performs a post stemming process and returns the final stem.
     *
     * @param array $stems
     * @param string $originalWord
     * @return string
     */
    public function postProcess(array $stems, string $originalWord): string
    {
        $finalStems = [];
        if (\in_array($originalWord, $stems, true)) {
            unset($stems[\array_search($originalWord, $stems, true)]);
        }
        foreach ($stems as $stem) {
            if (Helper::countSyllables($stem) > 0) {
                $finalStems[] = $this->lastConsonant($stem);
            }
        }

        usort($finalStems, function ($s1, $s2) {
            if (\in_array($s1, $this->averageStemSizeExceptions, true)) {
                return -1;
            }
            if (\in_array($s2, $this->averageStemSizeExceptions, true)) {
                return 1;
            }
            $averageDistance = abs(mb_strlen($s1) - Helper::AVERAGE_STEMMED_SIZE) - abs(mb_strlen($s2) - Helper::AVERAGE_STEMMED_SIZE);

            return $averageDistance === 0 ? mb_strlen($s1) - mb_strlen($s2) : $averageDistance;
        });

        return $finalStems ? $finalStems[0] : $originalWord;
    }

    /**
     * Given the initial state of a state machine, it adds possible stems to an array of stems.
     *
     * @param StateInterface $initialState
     * @param string $word
     * @param array $stems
     * @param string $machine - a string representing the name of the state machine. It is used for debugging reasons only.
     * @return array
     */
    private function _genericSuffixStripper(StateInterface $initialState, string $word, array $stems, string $machine): array
    {
        $transitions = [];
        $initialState->addTransitions($word, $transitions, false);

        while ($transitions) {
            /** @var Transition $transition */
            $transition = \array_shift($transitions);
            $wordToStem = $transition->getWord();
            $stem = $this->stemWord($wordToStem, $transition->getSuffix());
            if ($stem !== $wordToStem) {
                if ($transition->getNextState()->isFinalState()) {
                    foreach ($transitions as $key => $transitionToRemove) {
                        /** @var Transition $transitionToRemove */
                        if ($transitionToRemove->isMarked() ||
                            (
                                $transitionToRemove->getStartState() === $transition->getStartState() &&
                                $transitionToRemove->getNextState() === $transition->getNextState()
                            )
                        ) {
                            unset($transitions[$key]);
                        }
                    }
                    $stems[] = $stem;
                    $transitions = $transition->getNextState()->addTransitions($stem, $transitions, false);

                } else {
                    foreach ($transition->similarTransitions([$transition]) as $similar) {
                        /** @var Transition $similar */
                        $similar->setMarked(true);
                    }
                    $transitions = $transition->getNextState()->addTransitions($stem, $transitions, true);
                }
            }
        }

        return $stems;
    }

    /**
     * Removes a certain suffix from the given word.
     *
     * @param string $word
     * @param Suffix $suffix
     * @return string
     */
    private function stemWord(string $word, Suffix $suffix): string
    {
        $stemmedWord = $word;
        if ($this->shouldBeMarked($word, $suffix) && $suffix->match($word)) {
            $stemmedWord = $suffix->removeSuffix($word);
        }
        $optionalLetter = $suffix->optionalLetter($stemmedWord);
        if ($optionalLetter) {
            if (Helper::validOptionalLetter($stemmedWord, $optionalLetter)) {
                $stemmedWord = mb_substr($stemmedWord, 0, -1);
            } else {
                $stemmedWord = $word;
            }
        }

        return $stemmedWord;
    }


    private function nominalVerbSuffixStripper(string $word, array $stems): array
    {
        $initialState = $this->container->get('NominalVerbState::A');
        return $this->_genericSuffixStripper($initialState, $word, $stems, 'NominalVerb');
    }

    private function nounSuffixStripper(string $word, array $stems): array
    {
        $initialState = $this->container->get('NounState::A');
        return $this->_genericSuffixStripper($initialState, $word, $stems, 'Noun');
    }

    private function derivationalSuffixStripper(string $word, array $stems): array
    {
        $initialState = $this->container->get('DerivationalState::A');
        return $this->_genericSuffixStripper($initialState, $word, $stems, 'Derivational');
    }

    /**
     * Checks if a word should be stemmed or not.
     *
     * @param string $word
     * @param Suffix $suffix
     * @return bool
     */
    private function shouldBeMarked(string $word, $suffix): bool
    {
        $word = Helper::toLower($word);
        return !\in_array($word, $this->protectedWords, true) &&
            (
                (
                    $suffix->getCheckHarmony() &&
                    (Helper::hasVowelHarmony($word) || \in_array($word, $this->vowelHarmonyExceptions, true))
                ) ||
                !$suffix->getCheckHarmony() );
    }

    /**
     * Checks the last consonant rule of a word.
     *
     * @param string $word
     * @return string
     */
    private function lastConsonant(string $word): string
    {
        if (\in_array(Helper::toLower($word), $this->lastConsonantExceptions, true)) {
            return $word;
        }

        $chars = Helper::split($word);
        $lastChar = $chars[\count($chars)- 1];
        if (isset(Helper::LAST_CONSONANT_RULES[$lastChar])) {
            unset($chars[\count($chars)- 1]);
            $chars[] = Helper::LAST_CONSONANT_RULES[$lastChar];
            return implode('', $chars);
        }

        return $word;
    }
}
