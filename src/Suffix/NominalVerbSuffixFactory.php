<?php
namespace TurkishStemmer\Suffix;

use TurkishStemmer\ContainerInterface;

class NominalVerbSuffixFactory
{
    protected $container = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $container->set('NominalVerbSuffix::S11', new Suffix('-cAsInA',    'casına|çasına|cesine|çesine',      null, true));
        $container->set('NominalVerbSuffix::S4',  new Suffix('-sUnUz',     'sınız|siniz|sunuz|sünüz',          null, true));
        $container->set('NominalVerbSuffix::S14', new Suffix('-(y)mUş',    'muş|miş|müş|mış',                  'y',  true));
        $container->set('NominalVerbSuffix::S15', new Suffix('-(y)ken',    'ken',                              'y',  true));
        $container->set('NominalVerbSuffix::S2',  new Suffix('-sUn',       'sın|sin|sun|sün',                  null, true));
        $container->set('NominalVerbSuffix::S5',  new Suffix('-lAr',       'lar|ler',                          null, true));
        $container->set('NominalVerbSuffix::S9',  new Suffix('-nUz',       'nız|niz|nuz|nüz',                  null, true));
        $container->set('NominalVerbSuffix::S10', new Suffix('-DUr',       'tır|tir|tur|tür|dır|dir|dur|dür',  null, true));
        $container->set('NominalVerbSuffix::S3',  new Suffix('-(y)Uz',     'ız|iz|uz|üz',                      'y',  true));
        $container->set('NominalVerbSuffix::S1',  new Suffix('-(y)Um',     'ım|im|um|üm',                      'y',  true));
        $container->set('NominalVerbSuffix::S12', new Suffix('-(y)DU',     'dı|di|du|dü|tı|ti|tu|tü',          'y',  true));
        $container->set('NominalVerbSuffix::S13', new Suffix('-(y)sA',     'sa|se',                            'y',  true));
        $container->set('NominalVerbSuffix::S6',  new Suffix('-m',         'm',                                null, true));
        $container->set('NominalVerbSuffix::S7',  new Suffix('-n',         'n',                                null, true));
        $container->set('NominalVerbSuffix::S8',  new Suffix('-k',         'k',                                null, true));
    }

    public function values(): array
    {
        return [
            $this->container->get('NominalVerbSuffix::S11'),
            $this->container->get('NominalVerbSuffix::S4'),
            $this->container->get('NominalVerbSuffix::S14'),
            $this->container->get('NominalVerbSuffix::S15'),
            $this->container->get('NominalVerbSuffix::S2'),
            $this->container->get('NominalVerbSuffix::S5'),
            $this->container->get('NominalVerbSuffix::S9'),
            $this->container->get('NominalVerbSuffix::S10'),
            $this->container->get('NominalVerbSuffix::S3'),
            $this->container->get('NominalVerbSuffix::S1'),
            $this->container->get('NominalVerbSuffix::S12'),
            $this->container->get('NominalVerbSuffix::S13'),
            $this->container->get('NominalVerbSuffix::S6'),
            $this->container->get('NominalVerbSuffix::S7'),
            $this->container->get('NominalVerbSuffix::S8'),
        ];
    }
}
