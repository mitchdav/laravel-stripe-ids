<?php

namespace Mitchdav\StripeIds;

/**
 * @property static $stripeIdsAlphabet
 * @property static $stripeIdsLength
 * @property static $stripeIdsSeparator
 * @property static $stripeIdsPrefix
 */
trait HasStripeId
{
    protected static function bootHasStripeId()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $stripeIds = new StripeIds(
                    self::$stripeIdsAlphabet ?? config('stripe-ids.alphabet'),
                    self::$stripeIdsLength ?? config('stripe-ids.length'),
                    self::$stripeIdsSeparator ?? config('stripe-ids.separator'),
                );

                $model->{$model->getKeyName()} = $stripeIds->id(self::$stripeIdsPrefix);
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
}