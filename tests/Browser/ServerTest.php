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
}
