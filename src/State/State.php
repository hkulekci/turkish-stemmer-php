<?php
namespace TurkishStemmer\State;

use TurkishStemmer\Suffix\Suffix;
use TurkishStemmer\Transition;

class State implements StateInterface
{
    protected bool $initialState;
    protected bool $finalState;
    protected mixed $suffixes;

    protected $storage;

    public function __construct(
        bool $initialState,
        bool $finalState,
        $suffixes = []
    ) {
        $this->initialState = $initialState;
        $this->finalState = $finalState;
        $this->suffixes = $suffixes;
    }

    public function addTransitions(string $word, array &$transitions, bool $marked): array
    {
        /** @var Suffix $suffix */
        foreach ($this->suffixes as $suffix) {
            if ($suffix->match($word)) {
                $transitions[] = new Transition($this, $this->nextState($suffix), $word, $suffix, $marked);
            }
        }

        return $transitions;
    }

    public function isInitialState(): bool
    {
        return $this->initialState;
    }

    public function isFinalState(): bool
    {
        return $this->finalState;
    }

    public function isEqual(StateInterface $state): bool
    {
        return $this->isInitialState() === $state->isInitialState() &&
            $this->isFinalState() === $state->isFinalState();
    }

    public function nextState(Suffix $suffix): ?StateInterface {
        throw new \Exception("Feature is not implemented.");
    }
}
