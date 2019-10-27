<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Server extends Model
{
    protected $fillable = ['name', 'url', 'username', 'password', 'console_url', 'console_username', 'console_password', 'hostname', 'notes'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function setConsolePasswordAttribute($value)
    {
        $this->attributes['console_password'] = Crypt::encryptString($value);
    }

}
