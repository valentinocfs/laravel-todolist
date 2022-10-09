<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testLoginPage()
    {
        $this->get('/login')
            ->assertSeeText('Login');
    }

    public function testLoginPageForMember()
    {
        $this->withSession([
            'user' => 'johndoe'
        ])->get('/login')
            ->assertRedirect('/');
    }

    public function testLoginSuccess()
    {
        $this->post('/login', [
            'user' => 'johndoe',
            'password' => 'rahasia',
        ])->assertRedirect('/')
            ->assertSessionHas('user', 'johndoe');
    }

    public function testLoginForUserAlreadyLogin()
    {
        $this->withSession([
            "user" => "johndoe"
        ])->post('/login', [
            "user" => "johndoe",
            "password" => "rahasia"
        ])->assertRedirect("/");
    }

    public function testLoginValidationError()
    {
        $this->post('/login', [])
            ->assertSeeText('User and Password is required');
    }

    public function testLoginFailed()
    {
        $this->post('/login', [
            'user' => 'admin',
            'password' => 'wrongpassword',
        ])->assertSeeText('User or Password is wrong');
    }

    public function testLogout()
    {
        $this->withSession([
            'user' => 'johndoe'
        ])->post('/logout')
             ->assertRedirect('/')
             ->assertSessionMissing('user');
    }
}
