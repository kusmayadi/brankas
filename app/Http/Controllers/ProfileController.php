<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile');
    }

    public function save(Request $request)
    {
        Auth::user()->name = $request->name;
        Auth::user()->email = $request->email;
        Auth::user()->save();

        return redirect()->route('profile.index')->with('message', 'Your profile has been saved.');
    }
}
