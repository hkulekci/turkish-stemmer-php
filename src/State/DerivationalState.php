<?php
namespace TurkishStemmer\State;

use TurkishStemmer\ContainerInterface;
use TurkishStemmer\Suffix\Suffix;

class DerivationalState extends State
{
    protected $container;

    public function __construct(ContainerInterface $container, bool $initialState, bool $finalState, array $suffixes = [])
    {
        $this->container = $container;
        parent::__construct($initialState, $finalState, $suffixes);
    }

    public function nextState(Suffix $suffix): ?StateInterface
    {
        if ($this->isInitialState()) {
            return $this->container->get('DerivationalState::B');
        }
    }
}
