<?php
namespace TurkishStemmer;

class Helper
{
    // The turkish characters. They are used for skipping not turkish words.
    public const ALPHABET = ['a','b','c','ç','d','e','f','g','ğ','h','ı','i','j','k','l','m','n','o','ö','p','r','s','ş','t','u','ü','v','y','z'];

    // The turkish vowels.
    public const VOWELS = ['ü','i','ı','u','e','ö','a','o'];

    // The turkish consonants.
    public const CONSONANTS = ['b','c','ç','d','f','g','ğ','h','j','k','l','m','n','p','r','s','ş','t','v','y','z'];

    // Rounded vowels which are used for checking roundness harmony.
    public const ROUNDED_VOWELS = ['o','ö','u','ü'];

    // Vowels that follow rounded vowels. They are combined with ROUNDED_VOWELS to check roundness harmony.
    public const FOLLOWING_ROUNDED_VOWELS = ['a','e','u','ü'];

    // The unrounded vowels which are used for checking roundness harmony.
    public const UNROUNDED_VOWELS = ['i','ı','e','a'];

    // Front vowels which are used for checking frontness harmony.
    public const FRONT_VOWELS = ['e','i','ö','ü'];

    // Front vowels which are used for checking frontness harmony.
    public const BACK_VOWELS = ['ı', 'u', 'a', 'o',];

    // Last consonant rules
    public const LAST_CONSONANT_RULES = ['b' => 'p', 'c' => 'ç', 'd' => 't', 'ğ' => 'k'];

    // The path of the file that contains the default set of protected words.
    public const DEFAULT_PROTECTED_WORDS_FILE = __DIR__.'/../resources/protected_words.txt';

    // The path of the file that contains the default set of vowel harmony exceptions.
    public const DEFAULT_VOWEL_HARMONY_EXCEPTIONS_FILE = __DIR__.'/../resources/vowel_harmony_exceptions.txt';

    // The path of the file that contains the default set of last consonant exceptions.
    public const DEFAULT_LAST_CONSONANT_EXCEPTIONS_FILE = __DIR__.'/../resources/last_consonant_exceptions.txt';

    // The path of the file that contains the default set of average stem size exceptions.
    public const DEFAULT_AVERAGE_STEM_SIZE_EXCEPTIONS_FILE = __DIR__.'/../resources/average_stem_size_exceptions.txt';

    // The average size of turkish stems based on which the selection of the final stem is performed.
    // The idea behind the selection process is based on the paper
    // F.Can, S.Kocberber, E.Balcik, C.Kaynak, H.Cagdas, O.Calan, O.Vursavas
    // 'Information Retrieval on Turkish Texts'
    public const AVERAGE_STEMMED_SIZE = 4;

    public static function split($word): array
    {
        return preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
//        return array_map(function ($i) use ($word) {
//            return mb_substr($word, $i, 1);
//        }, range(0, mb_strlen($word) -1));
    }

    public static function toLower(string $word): string
    {
        $chars = [];
        foreach (self::split($word) as $char)
        {
            switch ($char) {
                case 'Ğ': $chars[] = 'ğ'; break;
                case 'İ': $chars[] = 'i'; break;
                case 'I': $chars[] = 'ı'; break;
                case 'Ü': $chars[] = 'ü'; break;
                case 'Ö': $chars[] = 'ö'; break;
                case 'Ç': $chars[] = 'ç'; break;
                case 'Ş': $chars[] = 'ş'; break;
                default: $chars[] = mb_strtolower($char);
            }
        }
        return implode('', $chars);
    }

    /**
     * Checks whether a word is written in Turkish alphabet or not.
     *
     * @param string $word
     * @return bool
     */
    public static function isTurkish(string $word): bool
    {
        foreach (self::split(self::toLower($word)) as $char) {
            if (!\in_array($char, self::ALPHABET, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets the vowels of a word.
     *
     * @param string $word
     * @return string
     */
    public static function vowels(string $word): string
    {
        $chars = [];
        foreach (self::split($word) as $char) {
            if (\in_array(self::toLower($char), self::VOWELS, true)) {
                $chars[] = $char;
            }
        }
        return implode('', $chars);
        //return "".join(n for n in word if n in VOWELS)
    }

    /**
     * Gets the number of syllables of a word.
     *
     * @param string $word
     * @return int
     */
    public static function countSyllables(string $word): int
    {
        return mb_strlen(self::vowels($word));
    }

    /**
     * Checks the frontness harmony of two characters.
     *
     * @param string $vowel  the first character
     * @param string $candidate  candidate the second character
     * @return bool
     */
    private static function hasFrontness(string $vowel, string $candidate): bool
    {
        return (
                \in_array(self::toLower($vowel), self::FRONT_VOWELS, true) &&
                \in_array(self::toLower($candidate), self::FRONT_VOWELS, true)
            ) || (
                \in_array(self::toLower($vowel), self::BACK_VOWELS, true) &&
                \in_array(self::toLower($candidate), self::BACK_VOWELS, true)
            );
    }

    /**
     * Checks the roundness harmony of two characters.
     *
     * @param string $vowel  the first character
     * @param string $candidate  candidate the second character
     * @return bool
     */
    private static function hasRoundness(string $vowel, string $candidate): bool
    {
        return (
                \in_array(self::toLower($vowel), self::UNROUNDED_VOWELS, true) &&
                \in_array(self::toLower($candidate), self::UNROUNDED_VOWELS, true)
            ) || (
                \in_array(self::toLower($vowel), self::ROUNDED_VOWELS, true) &&
                \in_array(self::toLower($candidate), self::FOLLOWING_ROUNDED_VOWELS, true)
            );
    }

    /**
     * Checks the vowel harmony of two characters.
     *
     * @param string $vowel  the first character
     * @param string $candidate  candidate the second character
     * @return bool
     */
    private static function vowelHarmony(string $vowel, string $candidate): bool
    {
        return self::hasRoundness($vowel, $candidate) && self::hasFrontness($vowel, $candidate);
    }

    /**
     * Checks the vowel harmony of a word.
     *
     * @param string $word
     * @return bool
     */
    public static function hasVowelHarmony(string $word): bool
    {
        $vowels = self::split(self::vowels($word));
        if (!isset($vowels[\count($vowels) - 2])) {
            return true;
        }

        if (!isset($vowels[\count($vowels) - 1])) {
            return true;
        }

        return self::vowelHarmony($vowels[\count($vowels) - 2], $vowels[\count($vowels) - 1]);
    }

    /**
     * Checks whether an optional letter is valid or not.
     *
     * @param string $word
     * @param string $candidate
     * @return bool
     */
    public static function validOptionalLetter(string $word, string $candidate): bool
    {
        $chars = self::split($word);
        if (!isset($chars[\count($chars) - 2])) {
            return false;
        }
        $previousChar = self::toLower($chars[\count($chars) - 2]);
        if (\in_array($candidate, self::VOWELS, true)) {
            return \in_array($previousChar, self::CONSONANTS, true);
        }

        return \in_array($previousChar, self::VOWELS, true);
    }

    /**
     * Creates a set from a file
     *
     * @param string $path
     * @return array
     */
    public static function loadWordSet(string $path): array
    {
        $result = [];
        if (!file_exists($path)) {
            throw new \RuntimeException('wordset file not found! ['.$path.']');
        }
        $f = fopen($path, 'r');
        while ($line = stream_get_line($f, 1024 * 1024, "\n")) {
            $result[] = trim($line);
        }
        fclose($f);

        return $result;
    }
}
