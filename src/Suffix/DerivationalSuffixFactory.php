<?php
namespace TurkishStemmer\Suffix;

use TurkishStemmer\ContainerInterface;

class DerivationalSuffixFactory
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->container->set('DerivationalSuffix::S1', new Suffix('-lU', 'lı|li|lu|lü', null, true));
    }

    public function values(): array
    {
        return [
            $this->container->get('DerivationalSuffix::S1')
        ];
    }
}
