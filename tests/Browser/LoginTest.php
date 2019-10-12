<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testHome()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertPathIs('/login');                    // Make sure it redirected to login page
        });
    }

    public function testLogin()
    {
        $user = factory(User::class)->create([
            'email' => 'test@user.com',
            'password' => Hash::make('test')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'test')
                    ->press('Login')
                    ->assertPathIs('/home');
        });
    }

    public function testLoginFailed()
    {
        $this->browse(function(Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'anonymous@anonymous.org')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee('These credentials do not match our records.');
        });
    }
}
