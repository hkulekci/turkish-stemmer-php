<?php
namespace TurkishStemmer\State;

use TurkishStemmer\ContainerInterface;
use TurkishStemmer\Suffix\NominalVerbSuffixFactory;
use TurkishStemmer\Suffix\Suffix;

class NominalVerbStateFactory
{
    public function __construct(ContainerInterface $container)
    {
        $nominalVerbSuffix = $container->get(NominalVerbSuffixFactory::class);

        $container->set('NominalVerbState::A', new NominalVerbState($container,true, false, $nominalVerbSuffix->values()));
        $container->set('NominalVerbState::B', new NominalVerbState($container,false, true, [$container->get('NominalVerbSuffix::S14')]));
        $container->set('NominalVerbState::C', new NominalVerbState($container,false, true, [$container->get('NominalVerbSuffix::S10'), $container->get('NominalVerbSuffix::S12'), $container->get('NominalVerbSuffix::S13'), $container->get('NominalVerbSuffix::S14')]));
        $container->set('NominalVerbState::D', new NominalVerbState($container,false, false, [$container->get('NominalVerbSuffix::S12'), $container->get('NominalVerbSuffix::S13')]));
        $container->set('NominalVerbState::E', new NominalVerbState($container,false, true, [$container->get('NominalVerbSuffix::S1'), $container->get('NominalVerbSuffix::S2'), $container->get('NominalVerbSuffix::S3'), $container->get('NominalVerbSuffix::S4'), $container->get('NominalVerbSuffix::S5'), $container->get('NominalVerbSuffix::S14')]));
        $container->set('NominalVerbState::F', new NominalVerbState($container,false, true));
        $container->set('NominalVerbState::G', new NominalVerbState($container,false, false, [$container->get('NominalVerbSuffix::S14')]));
        $container->set('NominalVerbState::H', new NominalVerbState($container,false, false, [$container->get('NominalVerbSuffix::S1'), $container->get('NominalVerbSuffix::S2'), $container->get('NominalVerbSuffix::S3'), $container->get('NominalVerbSuffix::S4'), $container->get('NominalVerbSuffix::S5'), $container->get('NominalVerbSuffix::S14')]));

        $this->initStates($container);
    }

    private function initStates(ContainerInterface $container): void
    {
        $tfValues = $ftValues = $ffValues =  [];

        /** @var Suffix $sfx */
        foreach ([$container->get('NominalVerbSuffix::S1'),$container->get('NominalVerbSuffix::S2'),$container->get('NominalVerbSuffix::S3'),$container->get('NominalVerbSuffix::S4')] as $sfx) {
            $tfValues[(string)$sfx] = $container->get('NominalVerbState::B');
        }
        $tfValues[(string)$container->get('NominalVerbSuffix::S5')] = $container->get('NominalVerbState::C');
        /** @var Suffix $sfx */
        foreach ([$container->get('NominalVerbSuffix::S6'),$container->get('NominalVerbSuffix::S7'),$container->get('NominalVerbSuffix::S8'),$container->get('NominalVerbSuffix::S9')] as $sfx) {
            $tfValues[(string)$sfx] = $container->get('NominalVerbState::D');
        }
        $tfValues[(string)$container->get('NominalVerbSuffix::S10')] = $container->get('NominalVerbState::E');
        /** @var Suffix $sfx */
        foreach ([$container->get('NominalVerbSuffix::S12'),$container->get('NominalVerbSuffix::S13'),$container->get('NominalVerbSuffix::S14'),$container->get('NominalVerbSuffix::S15')] as $sfx) {
            $tfValues[(string)$sfx] = $container->get('NominalVerbState::F');
        }
        $tfValues[(string)$container->get('NominalVerbSuffix::S11')] = $container->get('NominalVerbState::H');

        /** @var Suffix $sfx */
        foreach ([$container->get('NominalVerbSuffix::S1'),$container->get('NominalVerbSuffix::S2'),$container->get('NominalVerbSuffix::S3'),$container->get('NominalVerbSuffix::S4'),$container->get('NominalVerbSuffix::S5')] as $sfx) {
            $ftValues[(string)$sfx] = $container->get('NominalVerbState::G');
        }
        /** @var Suffix $sfx */
        foreach ([$container->get('NominalVerbSuffix::S10'),$container->get('NominalVerbSuffix::S12'),$container->get('NominalVerbSuffix::S13'),$container->get('NominalVerbSuffix::S14')] as $sfx) {
            $ftValues[(string)$sfx] = $container->get('NominalVerbState::F');
        }
        /** @var Suffix $sfx */
        foreach ([$container->get('NominalVerbSuffix::S1'),$container->get('NominalVerbSuffix::S2'),$container->get('NominalVerbSuffix::S3'),$container->get('NominalVerbSuffix::S4'),$container->get('NominalVerbSuffix::S5')] as $sfx) {
            $ffValues[(string)$sfx] = $container->get('NominalVerbState::G');
        }
        /** @var Suffix $sfx */
        foreach ([$container->get('NominalVerbSuffix::S12'),$container->get('NominalVerbSuffix::S13'),$container->get('NominalVerbSuffix::S14')] as $sfx) {
            $ffValues[(string)$sfx] = $container->get('NominalVerbState::F');
        }

        $container->set('NominalVerbState::tfValues', $tfValues);
        $container->set('NominalVerbState::ftValues', $ftValues);
        $container->set('NominalVerbState::ffValues', $ffValues);
    }
}
