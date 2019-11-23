<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Password;

class PasswordTest extends TestCase
{
    use DatabaseMigrations;

    public function setup() :void
    {
        parent::setup();

        $this->user = factory(User::class)->create();
    }

    /**
     * Test add password.
     *
     * @return void
     */
    public function testAdd()
    {
        $password = factory(Password::class)->make();

        $response = $this->actingAs($this->user)
                        ->post(route('pass.store'), [
                            '_token' => csrf_token(),
                            'name' => $password->name,
                            'url' => $password->url,
                            'login' => $password->login,
                            'password' => 'secret',
                            'notes' => $password->notes,
                            'user_id' => $this->user->id
                        ]);

        $response->assertStatus(302);
    }

    /**
     * Test edit password.
     *
     * @return void
     */
    public function testEdit()
    {
        $password = factory(Password::class)->create(['user_id' => $this->user->id]);
        $newPassword = factory(Password::class)->make();
        $otherUser = factory(User::class)->create();

        $response = $this->actingAs($this->user)
                        ->put(route('pass.update', $password->id), [
                            '_token' => csrf_token(),
                            'name' => $newPassword->name,
                            'url' => $newPassword->url,
                            'login' => $newPassword->login,
                            'password' => 'secret',
                            'notes' => $newPassword->notes
                        ]);

        $response->assertStatus(302);

        $responseOther = $this->actingAs($otherUser)
                            ->put(route('pass.update', $password->id), [
                                '_token' => csrf_token(),
                                'name' => $newPassword->name,
                                'url' => $newPassword->url,
                                'login' => $newPassword->login,
                                'password' => 'secret',
                                'notes' => $newPassword->notes
                            ]);

        $responseOther->assertStatus(403);
    }

    /**
     * Test delete password.
     *
     * @return void
     */
    public function testDelete()
    {
        $passwordOne = factory(Password::class)->create(['user_id' => $this->user->id]);
        $passwordTwo = factory(Password::class)->create(['user_id' => $this->user->id]);
        $otherUser = factory(User::class)->create();

        $response = $this->actingAs($this->user)
                        ->delete(route('pass.destroy', $passwordOne->id));

        $response->assertStatus(302);

        $responseOther = $this->actingAs($otherUser)
                        ->delete(route('pass.destroy', $passwordTwo->id));

        $responseOther->assertStatus(403);
    }
}
