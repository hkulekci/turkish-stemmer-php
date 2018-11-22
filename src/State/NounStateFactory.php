<?php
namespace TurkishStemmer\State;

use TurkishStemmer\ContainerInterface;
use TurkishStemmer\Suffix\NounSuffixFactory;
use TurkishStemmer\Suffix\Suffix;

class NounStateFactory
{
    public function __construct(ContainerInterface $container)
    {
        /** @var NounSuffixFactory $nounSuffix */
        $nounSuffix = $container->get(NounSuffixFactory::class);

        $container->set('NounState::A', new NounState($container, true, true, $nounSuffix->values()));
        $container->set('NounState::B', new NounState($container, false, true, [$container->get('NounSuffix::S1'), $container->get('NounSuffix::S2'), $container->get('NounSuffix::S3'), $container->get('NounSuffix::S4'), $container->get('NounSuffix::S5')]));
        $container->set('NounState::C', new NounState($container, false, false, [$container->get('NounSuffix::S6'), $container->get('NounSuffix::S7')]));
        $container->set('NounState::D', new NounState($container, false, false, [$container->get('NounSuffix::S10'), $container->get('NounSuffix::S13'), $container->get('NounSuffix::S14')]));
        $container->set('NounState::E', new NounState($container, false, true, [$container->get('NounSuffix::S1'), $container->get('NounSuffix::S2'), $container->get('NounSuffix::S3'), $container->get('NounSuffix::S4'), $container->get('NounSuffix::S5'), $container->get('NounSuffix::S6'), $container->get('NounSuffix::S7'), $container->get('NounSuffix::S18')]));
        $container->set('NounState::F', new NounState($container, false, false, [$container->get('NounSuffix::S6'), $container->get('NounSuffix::S7'), $container->get('NounSuffix::S18')]));
        $container->set('NounState::G', new NounState($container, false, true, [$container->get('NounSuffix::S1'), $container->get('NounSuffix::S2'), $container->get('NounSuffix::S3'), $container->get('NounSuffix::S4'), $container->get('NounSuffix::S5'), $container->get('NounSuffix::S18')]));
        $container->set('NounState::H', new NounState($container, false, true, [$container->get('NounSuffix::S1')]));
        $container->set('NounState::K', new NounState($container, false, true));
        $container->set('NounState::L', new NounState($container, false, true, [$container->get('NounSuffix::S18')]));
        $container->set('NounState::M', new NounState($container, false, true, [$container->get('NounSuffix::S1'), $container->get('NounSuffix::S2'), $container->get('NounSuffix::S3'), $container->get('NounSuffix::S4'), $container->get('NounSuffix::S5'), $container->get('NounSuffix::S6'), $container->get('NounSuffix::S6'), $container->get('NounSuffix::S7')]));

        $this->initStates($container);
    }

    private function initStates(ContainerInterface $container): void
    {
        $ttValues = $ftValues = $ffValues = [];
        /** @var Suffix $sfx */
        foreach ([$container->get('NounSuffix::S8'),$container->get('NounSuffix::S11'),$container->get('NounSuffix::S13')] as $sfx) {
            $ttValues[(string)$sfx] = $container->get('NounState::B');
        }
        /** @var Suffix $sfx */
        foreach ([$container->get('NounSuffix::S9'),$container->get('NounSuffix::S16')] as $sfx) {
            $ttValues[(string)$sfx] = $container->get('NounState::C');
        }
        $ffValues[(string)$container->get('NounSuffix::S18')] = $ftValues[(string)$container->get('NounSuffix::S18')] = $ttValues[(string)$container->get('NounSuffix::S18')] = $container->get('NounState::D');
        /** @var Suffix $sfx */
        foreach ([$container->get('NounSuffix::S10'),$container->get('NounSuffix::S17')] as $sfx) {
            $ttValues[(string)$sfx] = $container->get('NounState::E');
        }
        /** @var Suffix $sfx */
        foreach ([$container->get('NounSuffix::S12'),$container->get('NounSuffix::S14')] as $sfx) {
            $ttValues[(string)$sfx] = $container->get('NounState::F');
        }
        $ttValues[(string)$container->get('NounSuffix::S15')] = $container->get('NounState::G');

        /** @var Suffix $sfx */
        foreach ([$container->get('NounSuffix::S2'),$container->get('NounSuffix::S3'),$container->get('NounSuffix::S4'),$container->get('NounSuffix::S5'),$container->get('NounSuffix::S6')] as $sfx) {
            $ftValues[(string)$sfx] = $ttValues[(string)$sfx] = $container->get('NounState::H');
        }

        $ffValues[(string)$container->get('NounSuffix::S7')] = $ftValues[(string)$container->get('NounSuffix::S7')] = $ttValues[(string)$container->get('NounSuffix::S7')] = $container->get('NounState::K');
        $ftValues[(string)$container->get('NounSuffix::S1')] = $ttValues[(string)$container->get('NounSuffix::S1')] = $container->get('NounState::L');
        $ttValues[(string)$container->get('NounSuffix::S19')] = $container->get('NounState::M');

        $ffValues[(string)$container->get('NounSuffix::S13')] = $container->get('NounState::B');
        $ffValues[(string)$container->get('NounSuffix::S10')] = $container->get('NounState::E');
        $ffValues[(string)$container->get('NounSuffix::S14')] = $container->get('NounState::F');
        $ffValues[(string)$container->get('NounSuffix::S6')] = $container->get('NounState::H');

        $container->set('NounState::ttValues', $ttValues);
        $container->set('NounState::ftValues', $ftValues);
        $container->set('NounState::ffValues', $ffValues);
    }
}
