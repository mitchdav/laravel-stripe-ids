<?php

namespace Mitchdav\StripeIds\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mitchdav\StripeIds\HasStripeId;

class DefaultModel extends Model
{
    use HasStripeId;

    public static $stripeIdsPrefix = 'dm';

    protected static $unguarded = true;

    protected $table = 'test_models';
}