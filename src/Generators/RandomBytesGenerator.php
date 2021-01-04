<?php

namespace Mitchdav\StripeIds\Generators;

class RandomBytesGenerator implements GeneratorInterface
{
    /**
     * @param  int  $length
     * @return string
     * @throws \Exception
     */
    public function generate(int $length): string
    {
        return random_bytes($length);
    }
}