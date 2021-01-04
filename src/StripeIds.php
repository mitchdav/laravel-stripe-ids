<?php

namespace Mitchdav\StripeIds;

use Mitchdav\StripeIds\Generators\GeneratorInterface;

class StripeIds
{
    /**
     * @var GeneratorInterface
     */
    private $generator;

    /**
     * @var int
     */
    private $hashLength;

    /**
     * @var string
     */
    private $hashAlphabet;

    public function __construct(GeneratorInterface $generator, int $hashLength, string $hashAlphabet)
    {
        $this->generator = $generator;
        $this->hashLength = $hashLength;
        $this->hashAlphabet = $hashAlphabet;
    }

    public function id(string $prefix, $hashLength = null, $hashAlphabet = null)
    {
        return $prefix.$this->hash($hashLength, $hashAlphabet);
    }

    public function hash($length = null, $alphabet = null)
    {
        $hashLength = $length ?? $this->hashLength;
        $hashAlphabet = $alphabet ?? $this->hashAlphabet;
        $hashAlphabetLength = strlen($hashAlphabet);

        $bytes = $this->generator->generate($hashLength);

        return collect(str_split($bytes))
            ->map(function ($randomByte) use ($hashAlphabet, $hashAlphabetLength) {
                return $hashAlphabet[ord($randomByte) % $hashAlphabetLength];
            })
            ->join('');
    }
}