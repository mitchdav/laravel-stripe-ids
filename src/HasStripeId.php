<?php

namespace Mitchdav\StripeIds;

/**
 * @property string $stripeIdAlphabet
 * @property int $stripeIdLength
 * @property string $stripeIdSeparator
 * @property string $stripeIdPrefix
 */
trait HasStripeId
{
    protected static function bootHasStripeId()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $stripeIds = new StripeIds(
                    $model->getStripeIdAlphabet(),
                    $model->getStripeIdLength(),
                    $model->getStripeIdSeparator()
                );

                $model->{$model->getKeyName()} = $stripeIds->id($model->getStripeIdPrefix());
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getStripeIdAlphabet()
    {
        return $this->stripeIdAlphabet ?? config('stripe-ids.alphabet');
    }

    public function getStripeIdLength()
    {
        return $this->stripeIdLength ?? config('stripe-ids.length');
    }

    public function getStripeIdSeparator()
    {
        return $this->stripeIdSeparator ?? config('stripe-ids.separator');
    }

    public function getStripeIdPrefix()
    {
        return $this->stripeIdPrefix;
    }
}