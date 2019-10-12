<?php

namespace Tests\Browser;

use App\User;
use App\Password;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Crypt;

class PasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test password creation
     *
     * @return void
     */
     public function testAuthMiddleware()
     {
        $this->browse(function (Browser $browser) {
            $browser->visit('/pass')
                    ->assertPathIs('/login');
        });
     }

    /**
     * Test password creation
     *
     * @return void
     */
    public function testCreate()
    {
        $user = factory(User::class)->create();
        $password = factory(Password::class)->make();

        $this->browse(function (Browser $browser) use ($user, $password) {
            $browser->loginAs($user)
                    ->visit('/pass/create')
                    ->assertSee('Add new password')
                    ->type('name', $password->name)
                    ->type('url', $password->url)
                    ->type('login', $password->login)
                    ->type('password', $password->password)
                    ->type('notes', $password->notes)
                    ->press('Save')
                    ->assertPathIs('/pass')
                    ->assertSee($password->name . ' has been saved.');
        });

        /**
         * Assert created record is in database, exclude password
         */
        $this->assertDatabaseHas('passwords', [
            'name' => $password->name,
            'url' => $password->url,
            'login' => $password->login,
            'notes' => $password->notes
        ]);

        $storedPassword = Password::where('name', $password->name)->find(1);

        /* Assert the stored password is the same as factory generated password */
        $this->assertEquals(Crypt::decryptString($storedPassword->password), $password->password);
    }
}
