<?php

namespace Mitchdav\StripeIds\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Mitchdav\StripeIds\StripeIds;
use Mitchdav\StripeIds\Tests\Models\DefaultModel;
use Mitchdav\StripeIds\Tests\Models\OverriddenModel;

class StripeIdsTest extends TestCase
{
    const HASH_ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    const HASH_LENGTH = 16;

    const ITERATIONS = 10000;

    const PREFIXES = [
        'dm_' => DefaultModel::class,
        'om:' => OverriddenModel::class,
    ];

    /** @test */
    public function it_can_generate_hashes()
    {
        $stripeIds = new StripeIds(self::HASH_LENGTH, self::HASH_ALPHABET);

        $hash = $stripeIds->hash();

        $this->assertEquals(1, preg_match('/^['.self::HASH_ALPHABET.']+$/', $hash));
        $this->assertEquals(self::HASH_LENGTH, strlen($hash));
    }

    /** @test */
    public function it_can_generate_ids()
    {
        $stripeIds = new StripeIds(self::HASH_LENGTH, self::HASH_ALPHABET);

        $prefix = 'abc_';

        $id = $stripeIds->id($prefix);

        $this->assertEquals(1, preg_match('/^'.$prefix.'['.self::HASH_ALPHABET.']+$/', $id));
        $this->assertEquals(strlen($prefix) + self::HASH_LENGTH, strlen($id));
    }

    /** @test */
    public function it_can_generate_unique_hashes()
    {
        $stripeIds = new StripeIds(self::HASH_LENGTH, self::HASH_ALPHABET);

        $hashes = Collection::times(self::ITERATIONS)
            ->map(function () use ($stripeIds) {
                return $stripeIds->hash();
            });

        $this->assertCount(self::ITERATIONS, $hashes->unique());
    }

    /** @test */
    public function it_can_generate_unique_ids()
    {
        $stripeIds = new StripeIds(self::HASH_LENGTH, self::HASH_ALPHABET);

        $ids = Collection::times(self::ITERATIONS)
            ->map(function () use ($stripeIds) {
                return $stripeIds->id('abc');
            });

        $this->assertCount(self::ITERATIONS, $ids->unique());
    }

    /** @test */
    public function it_can_find_models_by_id()
    {
        $stripeIds = new StripeIds(self::HASH_LENGTH, self::HASH_ALPHABET);

        // Make a few default models

        DefaultModel::query()
            ->create();

        DefaultModel::query()
            ->create();

        /** @var DefaultModel $defaultModel */
        $defaultModel = DefaultModel::query()
            ->create();

        $this->assertEquals(3, DefaultModel::query()->count());

        OverriddenModel::query()
            ->create();

        OverriddenModel::query()
            ->create();

        /** @var OverriddenModel $overriddenModel */
        $overriddenModel = OverriddenModel::query()
            ->create();

        $this->assertEquals(3, OverriddenModel::query()->count());

        $defaultModelQuery = $stripeIds->findByStripeId($defaultModel->getKey(), self::PREFIXES);

        // Assert that the query is setup to find only default models

        $this->assertInstanceOf(DefaultModel::class, $defaultModelQuery->getModel());

        // Assert that the query finds the specific instance of the default model

        $this->assertEquals($defaultModel->getKey(), $defaultModelQuery->first()->getKey());

        $overriddenModelQuery = $stripeIds->findByStripeId($overriddenModel->getKey(), self::PREFIXES);

        // Assert that the query is setup to find only overridden models

        $this->assertInstanceOf(OverriddenModel::class, $overriddenModelQuery->getModel());

        // Assert that the query finds the specific instance of the overridden model

        $this->assertEquals($overriddenModel->getKey(), $overriddenModelQuery->first()->getKey());
    }

    /** @test */
    public function it_can_build_queries_using_missing_ids()
    {
        $stripeIds = new StripeIds(self::HASH_LENGTH, self::HASH_ALPHABET, self::PREFIXES);

        // Make a few default models

        DefaultModel::query()
            ->create();

        DefaultModel::query()
            ->create();

        /** @var DefaultModel $defaultModel */
        $defaultModel = DefaultModel::query()
            ->create();

        $this->assertEquals(3, DefaultModel::query()->count());

        OverriddenModel::query()
            ->create();

        OverriddenModel::query()
            ->create();

        /** @var OverriddenModel $overriddenModel */
        $overriddenModel = OverriddenModel::query()
            ->create();

        $this->assertEquals(3, OverriddenModel::query()->count());

        // Create a query using the correct prefix but for an id that doesn't exist

        $defaultModelQuery = $stripeIds->findByStripeId($defaultModel->getStripeIdPrefix().'ABC123');

        $this->assertInstanceOf(DefaultModel::class, $defaultModelQuery->getModel());

        // Assert that the query finds the specific instance of the default model

        $this->assertNull($defaultModelQuery->first());

        // Create a new query using the correct prefix but for an id that doesn't exist

        $overriddenModelQuery = $stripeIds->findByStripeId($overriddenModel->getStripeIdPrefix().'ABC123');

        $this->assertInstanceOf(OverriddenModel::class, $overriddenModelQuery->getModel());

        // Assert that the query finds the specific instance of the overridden model

        $this->assertNull($overriddenModelQuery->first());
    }

    /** @test */
    public function it_throws_an_exception_for_invalid_prefixes()
    {
        $stripeIds = new StripeIds(self::HASH_LENGTH, self::HASH_ALPHABET, self::PREFIXES);

        // Create a query using the correct prefix but for an id that doesn't exist

        $this->expectException(ModelNotFoundException::class);

        $query = $stripeIds->findByStripeId('xx_ABC123');
    }
}