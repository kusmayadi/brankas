<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class PasswordList extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/pass';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
                ->assertVisible('.container')
                ->assertSee('Add new password');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@btnAddNew' => '#btn-add-new',
        ];
    }
}
