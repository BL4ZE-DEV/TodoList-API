<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('register', function() {
    $user = User::factory()->make([
        'password' => Hash::make('password')
    ])->toArray();

    $response = $this->postJson('api/register', array_merge($user , [
        'password' => 'password',
        'password_confirmation' => 'password'
        
    ]));

    $response->assertStatus(201)
            ->assertJsonStructure([
                "message",
                "token",
                "user" => [
                    'name',
                    'email',
                    'nickname',
                    'userId' 
                ]
            ]);

    $responseData = $response->json();

    $this->assertEquals('User registered successfully', $responseData['message']);
    $this->assertArrayHasKey('token', $responseData);
    $this->assertArrayHasKey('user', $responseData);
    $this->assertEquals($user['email'], $responseData['user']['email']);
});

test('login',function () {
    $userData = User::factory()->make();
    
    $response = $this->postJson('api/login', [
        'email' => 'Honshanana@gmail.com',
        'password' => 'password1!'
    ]);

    $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                "data" => [
                    'name',
                    'email',
                    'nickname',
                    'userId' 
                ]
                ]);
});


