<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicTest()
    {

        $response = $this->postJson( '/api/verify', [
            'phone' => '+998994002396',
            'verify_code' => 12345
        ]);

        $response->assertStatus(200);
    }


}
