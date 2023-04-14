<?php
namespace TurkishStemmer\Suffix;

class Suffix
{
    protected string $name;
    protected string $pattern;
    protected ?string $optionalLetter;
    protected ?bool $checkHarmony;

    public function __construct(string $name, string $pattern, string $optionalLetter = null, bool $checkHarmony = null)
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->optionalLetter = $optionalLetter;
        $this->checkHarmony = $checkHarmony;
    }

    public function optionalLetter($word): ?string
    {
        if (($this->optionalLetter !== null) && preg_match('/('.$this->optionalLetter.')$/', $word, $matches)) {
            return $matches[0];
        }

        return null;
    }

    public function removeSuffix(string $word): string
    {
        return preg_replace('/(' . $this->pattern . ')$/', '', $word);
    }

    public function match(string $word): bool
    {
        return (bool) preg_match('/(' . $this->pattern . ')$/', $word);
    }

    public function getCheckHarmony(): bool
    {
        return $this->checkHarmony;
    }

    public function setCheckHarmony($checkHarmony): void
    {
        $this->checkHarmony = $checkHarmony;
    }

    public function __toString()
    {
        return $this->name;
    }
}
