<?php

namespace Tests;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use CreatesApplication;

    public User $user;
    public string $token;
    public array $headerToken;

    public function createUserWithToken(): void 
    {
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
        Cache::put($this->token, $this->user->toArray(), 7200);
        $this->headerToken = ['Authorization' => 'Bearer '. $this->token];
        request()->merge(['Authorization' => 'Bearer ' . $this->token]);
    }
}