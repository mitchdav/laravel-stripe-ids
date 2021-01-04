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
                $model->{$model->getKeyName()} = $model->getStripeId();
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

    public function getStripeIdHashAlphabet()
    {
        return $this->stripeIdHashAlphabet ?? null;
    }

    public function getStripeIdHashLength()
    {
        return $this->stripeIdHashLength ?? null;
    }

    public function getStripeIdPrefix()
    {
        return $this->stripeIdPrefix ?? null;
    }

    public function getStripeId()
    {
        /** @var StripeIds $stripeIds */
        $stripeIds = app(StripeIds::class);

        return $stripeIds->id(
            $this->getStripeIdPrefix(),
            $this->getStripeIdHashLength(),
            $this->getStripeIdHashAlphabet()
        );
    }
}