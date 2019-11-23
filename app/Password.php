<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Password extends Model
{
    protected $fillable = ['name', 'url', 'login', 'password', 'notes'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
