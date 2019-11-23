<?php

namespace App\Observers;

use App\Password;
use Auth;

class PasswordObserver
{
    /**
     * Handle the password "created" event.
     *
     * @param  \App\Password  $passwrod
     * @return void
     */
    public function creating(Password $password)
    {
        if (Auth::user()) {
            $password->user_id = Auth::user()->id;
        }
    }

    /**
     * Handle the password "updated" event.
     *
     * @param  \App\Password $password
     * @return void
     */
    public function updating(Password $password)
    {
        if (Auth::user()) {
            $password->user_id = Auth::user()->id;
        }
    }

    /**
     * Handle the password "deleted" event.
     *
     * @param  \App\Password $password
     * @return void
     */
    public function deleted(Password $password)
    {
        //
    }

    /**
     * Handle the password "restored" event.
     *
     * @param  \App\Password  $password
     * @return void
     */
    public function restored(Password $password)
    {
        //
    }

    /**
     * Handle the passsword "force deleted" event.
     *
     * @param  \App\Password $password
     * @return void
     */
    public function forceDeleted(Password $password)
    {
        //
    }
}
