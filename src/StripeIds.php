<?php

namespace Mitchdav\StripeIds;

class StripeIds
{
    /**
     * @var string
     */
    private $alphabet;

    /**
     * @var int
     */
    private $alphabetLength;

    /**
     * @var int
     */
    private $length;

    /**
     * @var string
     */
    private $separator;

    public function __construct(string $alphabet, int $length, string $separator)
    {
        $this->alphabet = $alphabet;
        $this->alphabetLength = strlen($alphabet);
        $this->length = $length;
        $this->separator = $separator;
    }

    public function id(string $prefix)
    {
        return $prefix.$this->separator.$this->hash();
    }

    public function hash()
    {
        return collect(str_split(random_bytes($this->length)))
            ->map(function ($randomByte) {
                return $this->alphabet[ord($randomByte) % $this->alphabetLength];
            })
            ->join('');
    }
}