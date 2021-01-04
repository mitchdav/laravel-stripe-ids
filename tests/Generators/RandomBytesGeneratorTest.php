<?php

namespace Mitchdav\StripeIds\Tests\Generators;

use Illuminate\Support\Collection;
use Mitchdav\StripeIds\Generators\RandomBytesGenerator;
use Mitchdav\StripeIds\StripeIds;
use Mitchdav\StripeIds\Tests\TestCase;

class RandomBytesGeneratorTest extends TestCase
{
    const HASH_ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    const HASH_LENGTH = 16;

    const ITERATIONS = 10000;

    /** @test */
    public function it_can_generate_hashes()
    {
        $stripeIds = new StripeIds(new RandomBytesGenerator(), self::HASH_LENGTH, self::HASH_ALPHABET);

        $hash = $stripeIds->hash();

        $this->assertEquals(1, preg_match('/^['.self::HASH_ALPHABET.']+$/', $hash));
        $this->assertEquals(self::HASH_LENGTH, strlen($hash));
    }

    /** @test */
    public function it_can_generate_ids()
    {
        $stripeIds = new StripeIds(new RandomBytesGenerator(), self::HASH_LENGTH, self::HASH_ALPHABET);

        $prefix = 'abc_';

        $id = $stripeIds->id($prefix);

        $this->assertEquals(1, preg_match('/^'.$prefix.'['.self::HASH_ALPHABET.']+$/', $id));
        $this->assertEquals(strlen($prefix) + self::HASH_LENGTH, strlen($id));
    }

    /** @test */
    public function it_can_generate_unique_hashes()
    {
        $stripeIds = new StripeIds(new RandomBytesGenerator(), self::HASH_LENGTH, self::HASH_ALPHABET);

        $hashes = Collection::times(self::ITERATIONS)
            ->map(function () use ($stripeIds) {
                return $stripeIds->hash();
            });

        $this->assertCount(self::ITERATIONS, $hashes->unique());
    }

    /** @test */
    public function it_can_generate_unique_ids()
    {
        $stripeIds = new StripeIds(new RandomBytesGenerator(), self::HASH_LENGTH, self::HASH_ALPHABET);

        $ids = Collection::times(self::ITERATIONS)
            ->map(function () use ($stripeIds) {
                return $stripeIds->id('abc');
            });

        $this->assertCount(self::ITERATIONS, $ids->unique());
    }
}