<?php

namespace Tests\Browser;

use App\User;
use App\Password;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Crypt;
use Tests\Browser\Pages\PasswordList;

class PasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() :void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * Make sure PassController is protected by Auth
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
        $user = $this->user;
        $password = factory(Password::class)->make();

        $this->browse(function (Browser $browser) use ($user, $password) {
            $browser->loginAs($user)
                    ->visit(new PasswordList)
                    ->click('@btnAddNew')
                    ->assertPathIs('/pass/create')
                    ->assertSee('Add new password')
                    ->assertPresent('#frm-password');

            $frmAction = $browser->attribute('#frm-password', 'action');

            $this->assertEquals($frmAction, route('pass.store'));

            $browser->type('name', $password->name)
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

    /**
     * Test password list
     */
    public function testList()
    {
        $user = $this->user;
        $passwords = factory(Password::class, 5)->create();

        $this->browse(function (Browser $browser) use ($user, $passwords) {
            $browser->loginAs($this->user)
                    ->visit('/pass')
                    ->assertVisible('#tb-list');

            foreach ($passwords as $password) {
                $browser->assertSee($password->name);
            }
        });
    }

    /**
     * Test update password
     */
    public function testUpdate()
    {
        $password = factory(Password::class)->create();
        $newPassword = factory(Password::class)->make();

        $this->browse(function (Browser $browser) use ($password, $newPassword) {
            $browser->visit(new PasswordList)
                    ->assertVisible('.btn-edit')
                    ->assertSee('Edit')
                    ->click('.btn-edit:first-child')
                    ->assertPathIs('/pass/' . $password->id . '/edit')
                    ->assertSee('Edit password')
                    ->assertPresent('#frm-password');

            $frmAction = $browser->attribute('#frm-password', 'action');
            $fieldName = $browser->value('#frm-password input[name="name"]');
            $fieldURL = $browser->value('#frm-password input[name="url"]');
            $fieldLogin = $browser->value('#frm-password input[name="login"]');
            $fieldPassword = $browser->value('#frm-password input[name="password"]');
            $fieldNotes = $browser->value('#frm-password textarea[name="notes"]');

            $this->assertEquals($frmAction, route('pass.update', $password->id));
            $this->assertEquals($fieldName, $password->name);
            $this->assertEquals($fieldURL, $password->url);
            $this->assertEquals($fieldLogin, $password->login);
            $this->assertEquals($fieldPassword, $password->password);
            $this->assertEquals($fieldNotes, $password->notes);

            $browser->type('name', $newPassword->name)
                    ->type('url', $newPassword->url)
                    ->type('login', $newPassword->login)
                    ->type('password', $newPassword->password)
                    ->type('notes', $newPassword->notes)
                    ->press('Save')
                    ->assertPathIs('/pass')
                    ->assertSee($newPassword->name . ' has been saved.');

            $savedPassword = Password::find($password->id);

            $this->assertEquals($savedPassword->name, $newPassword->name);
            $this->assertEquals($savedPassword->url, $newPassword->url);
            $this->assertEquals($savedPassword->login, $newPassword->login);
            $this->assertEquals(Crypt::decryptString($savedPassword->password), $newPassword->password);
            $this->assertEquals($savedPassword->notes, $newPassword->notes);
        });
    }

    /**
     * Test delete password
     */
    public function testDelete()
    {
        $password = factory(Password::class)->create();

        $this->browse(function (Browser $browser) use ($password) {
            $browser->visit(new PasswordList)
                    ->assertPresent('.frm-delete')
                    ->assertVisible('.btn-delete')
                    ->assertSee('Delete');

            $frmAction = $browser->attribute('.frm-delete', 'action');

            $this->assertEquals($frmAction, route('pass.destroy', $password->id));

            $browser->click('.btn-delete')
                    ->assertPathIs('/pass')
                    ->assertSee($password->name . ' has been removed.');

            $this->assertDatabaseMissing('passwords', [
                'name' => $password->name,
                'url' => $password->url,
                'login' => $password->login,
                'password' => Crypt::encryptString($password->password),
                'notes' => $password->notes
            ]);
        });
    }
}
