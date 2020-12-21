<?php

namespace Mitchdav\StripeIds\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mitchdav\StripeIds\StripeIds;
use Mitchdav\StripeIds\Tests\Models\DefaultModel;
use Mitchdav\StripeIds\Tests\Models\OverriddenModel;

class StripeIdsTest extends TestCase
{
    const ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    const LENGTH = 16;

    const SEPARATOR = '_';

    const ITERATIONS = 10000;

    const PREFIXES = [
        'dm' => DefaultModel::class,
        'om' => OverriddenModel::class,
    ];

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

    /** @test */
    public function it_can_find_models_by_id()
    {
        $stripeIds = new StripeIds(self::ALPHABET, self::LENGTH, self::SEPARATOR, self::PREFIXES);

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

        $defaultModelQuery = $stripeIds->findByStripeId($defaultModel->getKey());

        // Assert that the query is setup to find only default models

        $this->assertInstanceOf(DefaultModel::class, $defaultModelQuery->getModel());

        // Assert that the query finds the specific instance of the default model

        $this->assertEquals($defaultModel->getKey(), $defaultModelQuery->first()->getKey());

        $overriddenModelQuery = $stripeIds->findByStripeId($overriddenModel->getKey());

        // Assert that the query is setup to find only overridden models

        $this->assertInstanceOf(OverriddenModel::class, $overriddenModelQuery->getModel());

        // Assert that the query finds the specific instance of the overridden model

        $this->assertEquals($overriddenModel->getKey(), $overriddenModelQuery->first()->getKey());
    }

    /** @test */
    public function it_can_build_queries_using_missing_ids()
    {
        $stripeIds = new StripeIds(self::ALPHABET, self::LENGTH, self::SEPARATOR, self::PREFIXES);

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

        $defaultModelQuery = $stripeIds->findByStripeId($defaultModel->getStripeIdPrefix().$defaultModel->getStripeIdSeparator().'ABC123');

        $this->assertInstanceOf(DefaultModel::class, $defaultModelQuery->getModel());

        // Assert that the query finds the specific instance of the default model

        $this->assertNull($defaultModelQuery->first());

        // Create a new query using the correct prefix but for an id that doesn't exist

        $overriddenModelQuery = $stripeIds->findByStripeId($overriddenModel->getStripeIdPrefix().$overriddenModel->getStripeIdSeparator().'ABC123');

        $this->assertInstanceOf(OverriddenModel::class, $overriddenModelQuery->getModel());

        // Assert that the query finds the specific instance of the overridden model

        $this->assertNull($overriddenModelQuery->first());
    }

    /** @test */
    public function it_throws_an_exception_for_invalid_prefixes()
    {
        $stripeIds = new StripeIds(self::ALPHABET, self::LENGTH, self::SEPARATOR, self::PREFIXES);

        // Create a query using the correct prefix but for an id that doesn't exist

        $this->expectException(ModelNotFoundException::class);

        $query = $stripeIds->findByStripeId('xx_ABC123');
    }
}