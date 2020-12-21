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

        $prefix = $model->getStripeIdPrefix();
        $separator = $model->getStripeIdSeparator();

        $this->assertNull($model->getKey());

        $model->save();

        $this->assertNotNull($model->getKey());
        $this->assertStringStartsWith($prefix.$separator, $model->getKey());
    }

    /** @test */
    public function it_generates_an_id_using_overridden_properties()
    {
        /** @var OverriddenModel $model */
        $model = OverriddenModel::query()->make();

        $prefix = $model->getStripeIdPrefix();
        $alphabet = $model->getStripeIdAlphabet();
        $length = $model->getStripeIdLength();
        $separator = $model->getStripeIdSeparator();

        $this->assertNull($model->getKey());

        $model->save();

        $this->assertNotNull($model->getKey());
        $this->assertStringStartsWith($prefix.$separator, $model->getKey());

        $pattern = '/^'.$prefix.$separator.'['.$alphabet.']+$/';

        $this->assertEquals(1, preg_match($pattern, $model->getKey()));
        $this->assertEquals(strlen($prefix) + strlen($separator) + $length, strlen($model->getKey()));
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