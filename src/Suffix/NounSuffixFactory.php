<?php
namespace TurkishStemmer\Suffix;

use TurkishStemmer\ContainerInterface;

class NounSuffixFactory
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $container->set('NounSuffix::S16', new Suffix('-nDAn',     'ndan|ntan|nden|nten',      null,       true));
        $container->set('NounSuffix::S7', new Suffix('-lArI',     'ları|leri',                null,       true));
        $container->set('NounSuffix::S3', new Suffix('-(U)mUz',   'mız|miz|muz|müz',          'ı|i|u|ü',  true));
        $container->set('NounSuffix::S5', new Suffix('-(U)nUz',   'nız|niz|nuz|nüz',          'ı|i|u|ü',  true));
        $container->set('NounSuffix::S1', new Suffix('-lAr',      'lar|ler',                  null,       true));
        $container->set('NounSuffix::S14', new Suffix('-nDA',      'nta|nte|nda|nde',          null,       true));
        $container->set('NounSuffix::S15', new Suffix('-DAn',      'dan|tan|den|ten',          null,       true));
        $container->set('NounSuffix::S17', new Suffix('-(y)lA',    'la|le',                    'y',        true));
        $container->set('NounSuffix::S10', new Suffix('-(n)Un',    'ın|in|un|ün',              'n',        true));
        $container->set('NounSuffix::S19', new Suffix('-(n)cA',    'ca|ce',                    'n',        true));
        $container->set('NounSuffix::S4', new Suffix('-Un',       'ın|in|un|ün',              null,       true));
        $container->set('NounSuffix::S9', new Suffix('-nU',       'nı|ni|nu|nü',              null,       true));
        $container->set('NounSuffix::S12', new Suffix('-nA',       'na|ne',                    null,       true));
        $container->set('NounSuffix::S13', new Suffix('-DA',       'da|de|ta|te',              null,       true));
        $container->set('NounSuffix::S18', new Suffix('-ki',       'ki',                       null,       false));
        $container->set('NounSuffix::S2', new Suffix('-(U)m',     'm',                        'ı|i|u|ü',  true));
        $container->set('NounSuffix::S6', new Suffix('-(s)U',     'ı|i|u|ü',                  's',        true));
        $container->set('NounSuffix::S8', new Suffix('-(y)U',     'ı|i|u|ü',                  'y',        true));
        $container->set('NounSuffix::S11', new Suffix('-(y)A',     'a|e',                      'y',        true));
        $this->container = $container;
    }

    public function values(): array
    {
        return [
            $this->container->get('NounSuffix::S16'),
            $this->container->get('NounSuffix::S7'),
            $this->container->get('NounSuffix::S3'),
            $this->container->get('NounSuffix::S5'),
            $this->container->get('NounSuffix::S1'),
            $this->container->get('NounSuffix::S14'),
            $this->container->get('NounSuffix::S15'),
            $this->container->get('NounSuffix::S17'),
            $this->container->get('NounSuffix::S10'),
            $this->container->get('NounSuffix::S19'),
            $this->container->get('NounSuffix::S4'),
            $this->container->get('NounSuffix::S9'),
            $this->container->get('NounSuffix::S12'),
            $this->container->get('NounSuffix::S13'),
            $this->container->get('NounSuffix::S18'),
            $this->container->get('NounSuffix::S2'),
            $this->container->get('NounSuffix::S6'),
            $this->container->get('NounSuffix::S8'),
            $this->container->get('NounSuffix::S11'),
        ];
    }
}
