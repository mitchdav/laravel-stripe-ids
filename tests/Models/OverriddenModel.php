<?php

namespace Mitchdav\StripeIds\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mitchdav\StripeIds\HasStripeId;

class OverriddenModel extends Model
{
    use HasStripeId;

    protected static $unguarded = true;

    public $stripeIdHashAlphabet = 'ABCDEF123456';

    public $stripeIdHashLength = 10;

    public $stripeIdPrefix = 'om:';
}