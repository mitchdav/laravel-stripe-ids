<?php

namespace Mitchdav\StripeIds\Tests;

use StripeIds;

class FacadeTest extends TestCase
{
    /** @test */
    public function it_can_generate_hashes()
    {
        $hash = StripeIds::hash();

        $this->assertNotNull($hash);
    }

    /** @test */
    public function it_can_generate_ids()
    {
        $prefix = 'abc';

        $id = StripeIds::id($prefix);

        $this->assertNotNull($id);
        $this->assertStringStartsWith($prefix, $id);
    }
}