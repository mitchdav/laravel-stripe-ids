<?php

namespace Mitchdav\StripeIds\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Mitchdav\StripeIds\HasStripeId;

class ModelPrefix implements Rule
{
    /**
     * @var Collection
     */
    private $prefixes;

    public function __construct($models)
    {
        $this->prefixes = collect(Arr::wrap($models))
            ->map(function ($model) {
                /** @var HasStripeId $instance */
                $instance = app($model);

                return $instance->getStripeIdPrefix();
            })
            ->sort();
    }

    public function passes($attribute, $value)
    {
        return $this->prefixes->contains(function ($prefix) use ($value) {
            return Str::startsWith($value, $prefix);
        });
    }

    public function message()
    {
        if ($this->prefixes->count() === 1) {
            return 'The :attribute must be prefixed with "'.$this->prefixes->first().'".';
        } else {
            return 'The :attribute must be prefixed with one of the following: "'.$this->prefixes->join('", "').'"';
        }
    }
}