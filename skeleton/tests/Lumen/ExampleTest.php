<?php

namespace Tests\Lumen;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\LumenTestCase;

class ExampleTest extends LumenTestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @test
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->response->getContent(), $this->app->version()
        );
    }
}
