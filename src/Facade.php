<?php

namespace Mitchdav\StripeIds;

/**
 * @method static string hash()
 * @method static string id(string $prefix)
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return StripeIds::class;
    }
}