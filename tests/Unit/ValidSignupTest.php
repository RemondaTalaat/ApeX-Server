<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use DB;

class ValidSignupTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSignup()
    {
        $response = $this->json(
            'POST',
            '/api/SignUp',
            [
            'email' => "bebo@gmail.com",
            'password' => '1721998',
            'username' => 'rehab_hamdy',
            ]
        );
        $response->assertStatus(200);
        $loginResponse = $this->json(
            'POST',
            '/api/SignIn',
            [
            'username' => 'rehab_hamdy',
            'password' => '1721998'
            ]
        );
        $token = $loginResponse->json('token');
        $response1 = $this->json(
            'POST',
            '/api/SignOut',
            [
            'token' => $token
            ]
        );
        $response1->assertStatus(200);
        DB::table('users')->where('username', 'rehab_hamdy')->delete();
    }
}
