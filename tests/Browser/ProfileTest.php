<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class ProfileTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * Make sure PassController is protected by Auth
     * @return void
     */
    public function testAuthMiddleware()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile')
                    ->assertPathIs('/login');
        });
    }

    /**
     * Test profile page
     * @return void
     */
    public function testProfilePage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                    ->visit('/home')
                    ->click('#user-nav')
                    ->click('@profile')
                    ->assertPathIs('/profile')
                    ->assertSee('Your Profile')
                    ->assertPresent('#frm-profile');

            $fieldName = $browser->value('#frm-profile input[name="name"]');
            $fieldEmail = $browser->value('#frm-profile input[name="email"]');

            $this->assertEquals($fieldName, $this->user->name);
            $this->assertEquals($fieldEmail, $this->user->email);
        });
    }

    /**
     * Test save profile
     * @return void
     */
    public function testSave()
    {
        $newUser = factory(User::class)->make();

        $this->browse(function (Browser $browser) use ($newUser) {
            $browser->loginAs($this->user)
                    ->visit('/home')
                    ->click('#user-nav')
                    ->click('@profile')
                    ->assertPathIs('/profile')
                    ->assertSee('Your Profile')
                    ->assertPresent('#frm-profile')
                    ->type('name', $newUser->name)
                    ->type('email', $newUser->email)
                    ->press('Save')
                    ->assertPathIs('/profile')
                    ->assertSee('Your profile has been saved.');

            $user = User::find($this->user->id);

            // Make sure it's updated in database
            $this->assertEquals($user->name, $newUser->name);
            $this->assertEquals($user->email, $newUser->email);

            // Make sure profile form filled by the updated data
            $fieldName = $browser->value('#frm-profile input[name="name"]');
            $fieldEmail = $browser->value('#frm-profile input[name="email"]');

            $this->assertEquals($fieldName, $newUser->name);
            $this->assertEquals($fieldEmail, $newUser->email);
        });
    }
}
