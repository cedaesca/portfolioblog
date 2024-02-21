<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function login_route_is_defined(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    /** @test */
    public function login_route_returns_correct_view(): void
    {
        $response = $this->get('/login');

        $response->assertViewIs('auth.login');
    }
}
