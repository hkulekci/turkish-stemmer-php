<?php
namespace TurkishStemmer\State;

use TurkishStemmer\Suffix\Suffix;

interface StateInterface
{
    public function isFinalState(): bool;
    public function isInitialState(): bool;
    public function nextState(Suffix $suffix): ?StateInterface;
    public function addTransitions(string $word, array &$transitions, bool $marked): array;
    public function isEqual(StateInterface $state): bool;
}
