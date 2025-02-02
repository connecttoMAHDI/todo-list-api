<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('register test', function () {
    // Arrange: create the payload with some random data
    $payload = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password@1234'
    ];

    // Act: hit the register route
    $response = $this->post('/api/v1/auth/register', $payload);

    // Assert: 201 means successful registration
    $response->assertStatus(201);
});

/* test('login test', function () {
    // Arrange and Act: Login and get a token
    $token = $this->login();

    // Assert: presence of $token means successful Login
    $this->assertNotNull($token);
});
 */
/* test('logout test', function () {
    // Arrange: Login and get a token
    $token = $this->login();

    // Act: Logout
    $response = $this->post('/api/v1/auth/logout', [], [
        'Authorization' => "Bearer $token",
    ]);

    // Assert: 204 means successful logout
    $response->assertStatus(204);
});

test('refresh test', function () {
    // Arrange: Login and get a token
    $token = $this->login();

    // Act: refresh the token
    $response = $this->get('/api/v1/auth/refresh', [
        'Authorization' => "Bearer $token",
    ]);

    // Assert: 200 means successful refresh
    $response->assertStatus(200);
});

test('login with invalid credentials leads to 401', function () {
    // Arrange and Act: hit the route with invalid credentials
    $response = $this->post('/api/v1/auth/login', [
        'email' => 'wrongemail@example.com',
        'password' => 'wrongpassword',
    ]);

    // Assert: 401 means success
    $response->assertStatus(401);
});
 */
