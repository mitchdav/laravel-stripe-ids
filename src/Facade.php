<?php

namespace Mitchdav\StripeIds;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static string hash()
 * @method static string id(string $prefix)
 * @method static Builder findByStripeId(string $id)
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return StripeIds::class;
    }
}