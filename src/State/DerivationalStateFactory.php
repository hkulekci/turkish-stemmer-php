<?php
namespace TurkishStemmer\State;

use TurkishStemmer\ContainerInterface;
use TurkishStemmer\Suffix\DerivationalSuffixFactory;

class DerivationalStateFactory
{
    public function __construct(ContainerInterface $container)
    {
        /** @var DerivationalSuffixFactory $derivationalSuffix */
        $derivationalSuffix = $container->get(DerivationalSuffixFactory::class);

        $container->set('DerivationalState::A', new DerivationalState($container, true, false, $derivationalSuffix->values()));
        $container->set('DerivationalState::B', new DerivationalState($container, false, true));
    }
}
