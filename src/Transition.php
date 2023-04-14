<?php
namespace TurkishStemmer;

use TurkishStemmer\State\StateInterface;
use TurkishStemmer\Suffix\Suffix;

class Transition
{
    protected StateInterface $startState;
    protected StateInterface $nextState;
    protected string $word;
    protected Suffix $suffix;
    protected bool $marked;

    public function __construct(StateInterface $startState, StateInterface $nextState, $word, Suffix $suffix, bool $marked)
    {
        $this->startState = $startState;
        $this->nextState = $nextState;
        $this->word = $word;
        $this->suffix = $suffix;
        $this->marked = $marked;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function getSuffix(): Suffix
    {
        return $this->suffix;
    }

    public function getStartState(): StateInterface
    {
        return $this->startState;
    }

    public function getNextState(): StateInterface
    {
        return $this->nextState;
    }

    public function isMarked(): bool
    {
        return $this->marked;
    }

    public function setMarked(bool $marked): void
    {
        $this->marked = $marked;
    }


    public function similarTransitions(array $transitions = []): ?\Generator
    {
        /** @var Transition $transition */
        foreach ($transitions as $transition) {
            if ($this->startState === $transition->startState && $this->nextState === $transition->nextState) {
                yield $transition;
            }
        }
    }
}
