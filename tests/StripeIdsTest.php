<?php

namespace Mitchdav\StripeIds\Tests;

use Mitchdav\StripeIds\StripeIds;

class StripeIdsTest extends TestCase
{
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    const LENGTH = 16;

    const SEPARATOR = '_';

    const ITERATIONS = 10000;

    /** @test */
    public function it_can_generate_hashes()
    {
        $stripeIds = new StripeIds(self::ALPHABET, self::LENGTH, self::SEPARATOR);

        $hash = $stripeIds->hash();

        $this->assertEquals(1, preg_match('/^['.self::ALPHABET.']+$/', $hash));
        $this->assertEquals(self::LENGTH, strlen($hash));
    }

    /** @test */
    public function it_can_generate_ids()
    {
        $stripeIds = new StripeIds(self::ALPHABET, self::LENGTH, self::SEPARATOR);

        $prefix = 'abc';

        $id = $stripeIds->id($prefix);

        $this->assertEquals(1, preg_match('/^'.$prefix.self::SEPARATOR.'['.self::ALPHABET.']+$/', $id));
        $this->assertEquals(strlen($prefix) + strlen(self::SEPARATOR) + self::LENGTH, strlen($id));
    }

    /** @test */
    public function it_can_generate_unique_hashes()
    {
        $stripeIds = new StripeIds(self::ALPHABET, self::LENGTH, self::SEPARATOR);

        $hashes = collect(range(1, self::ITERATIONS))
            ->map(function () use ($stripeIds) {
                return $stripeIds->hash();
            });

        $this->assertCount(self::ITERATIONS, $hashes->unique());
    }

    /** @test */
    public function it_can_generate_unique_ids()
    {
        $stripeIds = new StripeIds(self::ALPHABET, self::LENGTH, self::SEPARATOR);

        $ids = collect(range(1, self::ITERATIONS))
            ->map(function () use ($stripeIds) {
                return $stripeIds->id('abc');
            });

        $this->assertCount(self::ITERATIONS, $ids->unique());
    }
}