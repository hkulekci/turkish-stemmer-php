<?php
namespace TurkishStemmerTest;

use PHPUnit\Framework\TestCase;
use TurkishStemmer\Helper;
use TurkishStemmer\Stemmer;

class HelperTest extends TestCase
{
    public function testIsTurkish(): void
    {
        $this->assertTrue(\TurkishStemmer\Helper::isTurkish('HaydÇÖŞİĞÜar'));
        $this->assertFalse(\TurkishStemmer\Helper::isTurkish('Hâydâr'));
    }

    public function testCountSyllables(): void
    {
        $this->assertEquals(5, \TurkishStemmer\Helper::countSyllables('HaydÇÖŞİĞÜar'));
        $this->assertEquals(1, \TurkishStemmer\Helper::countSyllables('Haydâr'));
        $this->assertEquals(2, \TurkishStemmer\Helper::countSyllables('ümit'));
    }

    public function testVowel(): void
    {
        $this->assertEquals('aÖİÜa', \TurkishStemmer\Helper::vowels('HaydÇÖŞİĞÜar'));
        $this->assertEquals('a', \TurkishStemmer\Helper::vowels('Haydâr'));
        $this->assertEquals('üi', \TurkishStemmer\Helper::vowels('ümit'));
    }

    public function testHasVowelHarmony(): void
    {

    }

    public function testValidOptionalLetter(): void
    {

    }

    public function testLoadWordSetWithInvalidPath(): void
    {
        $this->expectException(\RuntimeException::class);
        Helper::loadWordSet('/invalid/path/to/workd/on/it');
    }

    public function testLoadWordSetWithValidPath(): void
    {
        $list = Helper::loadWordSet(__DIR__ . '/../../resources/protected_words.txt');
        $this->assertArrayHasKey(0, $list);
        $this->assertEquals('abiye', $list[0]);
    }
}
