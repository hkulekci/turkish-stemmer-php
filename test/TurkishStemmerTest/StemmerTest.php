<?php
namespace TurkishStemmerTest;

use PHPUnit\Framework\TestCase;
use TurkishStemmer\Stemmer;

class StemmerTest extends TestCase
{
    /** @var Stemmer */
    protected Stemmer $stemmer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stemmer = new Stemmer();
        $this->assertEquals(Stemmer::class, \get_class($this->stemmer));
    }

    /**
     * @dataProvider dataProvider()
     */
    public function testStemmer($word1, $word2): void
    {
        $this->assertEquals($word1, $this->stemmer->stem($word2));
    }

    public function dataProvider(): array|\Generator
    {
        $i = 0;
        $f = fopen(__DIR__ . '/../resources/test_words.csv', 'r');
        while (($line = fgetcsv($f)) !== FALSE) {
            if ($i++ > 30) {
                break;
            }
            $base = explode(':', $line[1]);
            yield [$base[0], $line[0]];

        }
        fclose($f);
    }
}
