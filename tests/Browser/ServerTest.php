<?php

namespace Tests\Browser;

use App\User;
use App\Server;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Crypt;
use Tests\Browser\Pages\ServerList;

class ServerTest extends DuskTestCase
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
            $browser->visit('/server')
                    ->assertPathIs('/login')
                    ->visit('/server/create')
                    ->assertPathIs('/login')
                    ->visit('/server/edit')
                    ->assertPathIs('/login')
                    ->visit('/server/destroy')
                    ->assertPathIs('/login');
        });
    }

    /**
     * Test server creation
     *
     * @return void
     */
    public function testCreate()
    {
        $user = $this->user;
        $server = factory(Server::class)->make(['user_id' => $user->id]);

        $this->browse(function (Browser $browser) use ($user, $server) {
            $browser->loginAs($user)
                    ->visit(new ServerList)
                    ->click('@btnAddNew')
                    ->assertPathIs('/server/create')
                    ->assertSee('Add new server')
                    ->assertPresent('#frm-server');

            $frmAction = $browser->attribute('#frm-server', 'action');

            $this->assertEquals($frmAction, route('server.store'));

            $browser->type('name', $server->name)
                    ->type('url', $server->url)
                    ->type('username', $server->username)
                    ->type('password', $server->password)
                    ->type('console_url', $server->console_url)
                    ->type('console_username', $server->console_username)
                    ->type('console_password', $server->console_password)
                    ->type('hostname', $server->hostname)
                    ->type('notes', $server->notes)
                    ->press('Save')
                    ->assertPathIs('/server')
                    ->assertSee($server->name . ' has been saved.');
        });

        /**
         * Assert created record is in database, exclude password
         */
        $this->assertDatabaseHas('servers', [
            'name' => $server->name,
            'url' => $server->url,
            'username' => $server->username,
            'console_url' => $server->console_url,
            'console_username' => $server->console_username,
            'hostname' => $server->hostname,
            'notes' => $server->notes,
            'user_id' => $user->id
        ]);

        $storedServer = Server::where('name', $server->name)->find(1);

        /* Assert the stored password is the same as factory generated password */
        $this->assertEquals(Crypt::decryptString($storedServer->password), $server->password);
        $this->assertEquals(Crypt::decryptString($storedServer->console_password), $server->console_password);
    }

    /**
     * Test update server
     */
    public function testUpdate()
    {
        $user = $this->user;
        $server = factory(Server::class)->create(['user_id' => $user->id]);
        $newServer = factory(Server::class)->make();

        $this->browse(function (Browser $browser) use ($user, $server, $newServer) {
            $browser->loginAs($user)
                    ->visit(new ServerList)
                    ->assertVisible('.btn-edit')
                    ->assertSee('Edit')
                    ->click('.btn-edit')
                    ->assertPathIs('/server/' . $server->id . '/edit')
                    ->assertSee('Edit server')
                    ->assertPresent('#frm-server');

            $frmAction = $browser->attribute('#frm-server', 'action');
            $fieldName = $browser->value('#frm-server input[name="name"]');
            $fieldURL = $browser->value('#frm-server input[name="url"]');
            $fieldUsername = $browser->value('#frm-server input[name="username"]');
            $fieldPassword = $browser->value('#frm-server input[name="password"]');
            $fieldConsoleURL = $browser->value('#frm-server input[name="console_url"]');
            $fieldConsoleUsername = $browser->value('#frm-server input[name="console_username"]');
            $fieldConsolePassword = $browser->value('#frm-server input[name="console_password"]');
            $fieldHostname = $browser->value('#frm-server input[name="hostname"]');
            $fieldNotes = $browser->value('#frm-server textarea[name="notes"]');

            $this->assertEquals($frmAction, route('server.update', $server->id));
            $this->assertEquals($fieldName, $server->name);
            $this->assertEquals($fieldURL, $server->url);
            $this->assertEquals($fieldUsername, $server->username);
            $this->assertEquals($fieldPassword, $server->password);
            $this->assertEquals($fieldConsoleURL, $server->console_url);
            $this->assertEquals($fieldConsoleUsername, $server->console_username);
            $this->assertEquals($fieldConsolePassword, $server->console_password);
            $this->assertEquals($fieldHostname, $server->hostname);
            $this->assertEquals($fieldNotes, $server->notes);

            $browser->type('name', $newServer->name)
                    ->type('url', $newServer->url)
                    ->type('username', $newServer->username)
                    ->type('password', $newServer->password)
                    ->type('console_url', $newServer->console_url)
                    ->type('console_username', $newServer->console_username)
                    ->type('console_password', $newServer->console_password)
                    ->type('hostname', $newServer->hostname)
                    ->type('notes', $newServer->notes)
                    ->press('Save')
                    ->assertPathIs('/server')
                    ->assertSee($newServer->name . ' has been saved.');

            $savedServer = Server::find($server->id);

            $this->assertEquals($savedServer->name, $newServer->name);
            $this->assertEquals($savedServer->url, $newServer->url);
            $this->assertEquals($savedServer->username, $newServer->username);
            $this->assertEquals(Crypt::decryptString($savedServer->password), $newServer->password);
            $this->assertEquals($savedServer->console_url, $newServer->console_url);
            $this->assertEquals($savedServer->console_username, $newServer->console_username);
            $this->assertEquals(Crypt::decryptString($savedServer->console_password), $newServer->console_password);
            $this->assertEquals($savedServer->hostname, $newServer->hostname);
            $this->assertEquals($savedServer->notes, $newServer->notes);
        });
    }

    /**
     * Test delete server
     */
    public function testDelete()
    {
        $server = factory(Server::class)->create(['user_id' => $this->user->id]);

        $this->browse(function (Browser $browser) use ($server) {
            $browser->visit(new ServerList)
                    ->assertPresent('.frm-delete')
                    ->assertVisible('.btn-delete')
                    ->assertSee('Delete');

            $frmAction = $browser->attribute('.frm-delete', 'action');

            $this->assertEquals($frmAction, route('server.destroy', $server->id));

            $browser->click('.btn-delete')
                    ->assertPathIs('/server')
                    ->assertSee($server->name . ' has been removed.');

            $this->assertDatabaseMissing('servers', [
                'name' => $server->name,
                'url' => $server->url,
                'username' => $server->username,
                'password' => Crypt::encryptString($server->password),
                'console_url' => $server->console_url,
                'console_username' => $server->console_username,
                'console_password' => Crypt::encryptString($server->console_password),
                'notes' => $server->notes
            ]);
        });
    }
}
