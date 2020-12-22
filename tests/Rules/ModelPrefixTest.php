<?php

namespace Mitchdav\StripeIds\Tests\Rules;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mitchdav\StripeIds\Rules\ModelPrefix;
use Mitchdav\StripeIds\Tests\Models\DefaultModel;
use Mitchdav\StripeIds\Tests\Models\OverriddenModel;
use Mitchdav\StripeIds\Tests\TestCase;

class ModelPrefixTest extends TestCase
{
    /** @test */
    public function it_can_validate_model_prefix_individually()
    {
        $this->handleValidationExceptions();

        $validator = Validator::make([
            'model_id' => 'dm_ABC123',
        ], [
            'model_id' => [
                new ModelPrefix(DefaultModel::class),
            ],
        ]);

        $validated = $validator->validate();

        $this->assertArrayHasKey('model_id', $validated);
    }

    /** @test */
    public function it_can_validate_model_prefix_in_array()
    {
        $this->handleValidationExceptions();

        $validator = Validator::make([
            'model_id' => 'dm_ABC123',
        ], [
            'model_id' => [
                new ModelPrefix([
                    DefaultModel::class,
                ]),
            ],
        ]);

        $validated = $validator->validate();

        $this->assertArrayHasKey('model_id', $validated);
    }

    /** @test */
    public function it_can_invalidate_model_prefixes()
    {
        $this->handleValidationExceptions();

        $this->expectException(ValidationException::class);

        $validator = Validator::make([
            'model_id' => 'xx_ABC123',
        ], [
            'model_id' => [
                new ModelPrefix([
                    DefaultModel::class,
                ]),
            ],
        ]);

        $validator->validate();
    }

    /** @test */
    public function it_can_provide_meaningful_message_with_single_prefix()
    {
        $validator = Validator::make([
            'model_id' => 'xx_ABC123',
        ], [
            'model_id' => [
                new ModelPrefix([
                    DefaultModel::class,
                ]),
            ],
        ]);

        $errorMessage = $validator->errors()->get('model_id')[0];

        $this->assertStringContainsString('"dm_"', $errorMessage);
    }

    /** @test */
    public function it_can_provide_meaningful_message_with_multiple_prefixes()
    {
        $validator = Validator::make([
            'model_id' => 'xx_ABC123',
        ], [
            'model_id' => [
                new ModelPrefix([
                    DefaultModel::class,
                    OverriddenModel::class,
                ]),
            ],
        ]);

        $errorMessage = $validator->errors()->get('model_id')[0];

        $this->assertStringContainsString(
            '"'.app(DefaultModel::class)->getStripeIdPrefix().'"',
            $errorMessage
        );

        $this->assertStringContainsString(
            '"'.app(OverriddenModel::class)->getStripeIdPrefix().'"',
            $errorMessage
        );
    }
}