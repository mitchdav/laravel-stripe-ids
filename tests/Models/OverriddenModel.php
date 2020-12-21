<?php

namespace Mitchdav\StripeIds\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mitchdav\StripeIds\HasStripeId;

class OverriddenModel extends Model
{
    use HasStripeId;

    public static $stripeIdsAlphabet = 'ABCDEF123456';

    public static $stripeIdsLength = 10;

    public static $stripeIdsSeparator = ':';

    public static $stripeIdsPrefix = 'om';

    protected static $unguarded = true;

    protected $table = 'test_models';
}