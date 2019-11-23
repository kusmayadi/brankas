<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Password;
use Auth;

class PassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $passwords = Password::where('user_id', Auth::user()->id)->get();

        return view('pass.index', ['passwords' => $passwords]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pass.form', ['action' => 'create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Password::create($request->all());

        return redirect()->route('pass.index')->with('message', $request->name . ' has been saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $password = Password::find($id);

        return view('pass/form', ['password' => $password, 'action' => 'edit']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $password = Password::findOrFail($id);

        if (Auth::id() == $password->user_id) {
            $password->update($request->all());
            return redirect()->route('pass.index')->with('message', $request->name . ' has been saved.');
        } else {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $password = Password::find($id);

        if (Auth::id() == $password->user_id) {
            $password->delete();
            return redirect()->route('pass.index')->with('message', $password->name . ' has been removed.');
        } else {
            abort(403);
        }
    }
}
