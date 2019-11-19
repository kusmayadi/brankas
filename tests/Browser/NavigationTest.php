<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;
use App\Password;
use App\Server;

class NavigationTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        factory(Password::class, 5)->create();
        factory(Server::class, 5)->create();
    }


    /**
     * Test Not authenticated navigation.
     *
     * @return void
     */
    public function testNotAuthenticatedNavigation()
    {
        $this->browse(function (Browser $browser) {
            $browser ->visit('/')
                    ->assertDontSee('Passwords')
                    ->assertDontSee('Servers');
        });
    }

    /**
     * Test Navigation.
     *
     * @return void
     */
    public function testNavigation()
    {
        $user = $this->user;

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/home')
                    ->assertSee('Passwords')
                    ->assertSee('Servers')
                    ->clickLink('Passwords')
                    ->assertPathIs('/pass')
                    ->clickLink('Servers')
                    ->assertPathIs('/server');
        });
    }

    /**
     * Test Password Navigation.
     *
     * @return void
     */
    public function testPasswordNavigation()
    {
        $user = $this->user;

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/pass')
                    ->assertSeeIn('li.active > a', 'Passwords')
                    ->assertPresent('li.active > a > span.sr-only')
                    ->assertSeeIn('li.active > a > span.sr-only', '(current)')
                    ->clickLink('Add new password')
                    ->assertSeeIn('li.active > a', 'Passwords')
                    ->assertPresent('li.active > a > span.sr-only')
                    ->assertSeeIn('li.active > a > span.sr-only', '(current)')
                    ->clickLink('Passwords')
                    ->clickLink('Edit')
                    ->assertSeeIn('li.active > a', 'Passwords')
                    ->assertPresent('li.active > a > span.sr-only')
                    ->assertSeeIn('li.active > a > span.sr-only', '(current)');
        });
    }

    /**
     * Test Server Navigation.
     *
     * @return void
     */
    public function testServerNavigation()
    {
        $user = $this->user;

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/server')
                    ->assertSeeIn('li.active > a', 'Servers')
                    ->assertPresent('li.active > a > span.sr-only')
                    ->assertSeeIn('li.active > a > span.sr-only', '(current)')
                    ->clickLink('Add new server')
                    ->assertSeeIn('li.active > a', 'Servers')
                    ->assertPresent('li.active > a > span.sr-only')
                    ->assertSeeIn('li.active > a > span.sr-only', '(current)')
                    ->clickLink('Servers')
                    ->clickLink('Edit')
                    ->assertSeeIn('li.active > a', 'Servers')
                    ->assertPresent('li.active > a > span.sr-only')
                    ->assertSeeIn('li.active > a > span.sr-only', '(current)');
        });
    }
}
