<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class LumenExampleTest extends LumenTestCase
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
