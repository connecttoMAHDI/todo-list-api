<?php

namespace Tests;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function login()
    {
        $response = $this->post('/api/v1/auth/login', [
            'email' => User::inRandomOrder()->first()->id,
            'password' => 'Test@1234',
        ]);

        $data = json_decode(
            $response->getContent(),
            true
        );

        // Return only the token
        return $data['data']['token'];
    }

    public function createTodo()
    {
        $todo = Todo::create([
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => 'sample title',
            'description' => 'sample description',
        ]);

        return $todo;
    }
}
