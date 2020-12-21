<?php

namespace Mitchdav\StripeIds\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mitchdav\StripeIds\HasStripeId;

class DefaultModel extends Model
{
    use HasStripeId;

    protected static $unguarded = true;

    public $stripeIdPrefix = 'dm';

    protected $table = 'test_models';
}