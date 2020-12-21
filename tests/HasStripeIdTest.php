<?php

namespace Mitchdav\StripeIds\Tests;

use Mitchdav\StripeIds\Tests\Models\DefaultModel;
use Mitchdav\StripeIds\Tests\Models\OverriddenModel;

class HasStripeIdTest extends TestCase
{
    /** @test */
    public function it_generates_an_id_during_creating_event()
    {
        /** @var DefaultModel $model */
        $model = DefaultModel::query()->make();

        $this->assertNull($model->getKey());

        $model->save();

        $this->assertNotNull($model->getKey());
        $this->assertStringStartsWith(DefaultModel::$stripeIdsPrefix, $model->getKey());
    }

    /** @test */
    public function it_generates_an_id_using_overridden_properties()
    {
        /** @var OverriddenModel $model */
        $model = OverriddenModel::query()->make();

        $this->assertNull($model->getKey());

        $model->save();

        $this->assertNotNull($model->getKey());
        $this->assertStringStartsWith(OverriddenModel::$stripeIdsPrefix, $model->getKey());

        $pattern = '/^'.OverriddenModel::$stripeIdsPrefix.OverriddenModel::$stripeIdsSeparator.'['.OverriddenModel::$stripeIdsAlphabet.']+$/';

        $this->assertEquals(1, preg_match($pattern, $model->getKey()));
        $this->assertEquals(
            strlen(OverriddenModel::$stripeIdsPrefix) + strlen(OverriddenModel::$stripeIdsSeparator) + OverriddenModel::$stripeIdsLength,
            strlen($model->getKey())
        );
    }

    /** @test */
    public function it_ignores_creating_logic_if_key_is_already_set()
    {
        $testKey = 'test_key';

        /** @var OverriddenModel $model */
        $model = OverriddenModel::query()->make([
            'id' => $testKey,
        ]);

        $this->assertEquals($testKey, $model->getKey());

        $model->save();

        $this->assertEquals($testKey, $model->getKey());
    }
}