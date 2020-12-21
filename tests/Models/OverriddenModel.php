<?php

namespace Mitchdav\StripeIds\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Mitchdav\StripeIds\HasStripeId;

class OverriddenModel extends Model
{
    use HasStripeId;

    protected static $unguarded = true;

    public $stripeIdAlphabet = 'ABCDEF123456';

    public $stripeIdLength = 10;

    public $stripeIdSeparator = ':';

    public $stripeIdPrefix = 'om';

    protected $table = 'test_models';
}