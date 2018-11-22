<?php
namespace TurkishStemmer\State;

use TurkishStemmer\ContainerInterface;
use TurkishStemmer\Suffix\Suffix;

class NominalVerbState extends State
{
    protected $container;

    public function __construct(ContainerInterface $container, bool $initialState, bool $finalState, array $suffixes = [])
    {
        $this->container = $container;
        parent::__construct($initialState, $finalState, $suffixes);
    }

    public function nextState(Suffix $suffix):  ?StateInterface
    {
        if ($this->isInitialState() && !$this->isFinalState()) {
            $values = $this->container->get('NominalVerbState::tfValues');
        } elseif (!$this->isInitialState() && $this->isFinalState()) {
            $values = $this->container->get('NominalVerbState::ftValues');
        } else {
            $values = $this->container->get('NominalVerbState::ffValues');
        }

        return $values[(string)$suffix];
    }
}
