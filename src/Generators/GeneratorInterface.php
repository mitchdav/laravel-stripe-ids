<?php

namespace Mitchdav\StripeIds\Generators;

interface GeneratorInterface
{
    public function generate(int $length): string;
}