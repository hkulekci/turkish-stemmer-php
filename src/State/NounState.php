<?php
namespace TurkishStemmer\State;

use TurkishStemmer\ContainerInterface;
use TurkishStemmer\Suffix\Suffix;

class NounState extends State
{
    protected $container;

    public function __construct(ContainerInterface $container, bool $initialState, bool $finalState, array $suffixes = [])
    {
        $this->container = $container;
        parent::__construct($initialState, $finalState, $suffixes);
    }

    public function nextState(Suffix $suffix): ?StateInterface
    {
        if ($this->isInitialState() && $this->isFinalState()) {
            $values = $this->container->get('NounState::ttValues');
        } elseif (!$this->isInitialState() && $this->isFinalState()) {
            $values = $this->container->get('NounState::ftValues');
        } else {
            $values = $this->container->get('NounState::ffValues');
        }

        return $values[(string)$suffix];
    }
}
