@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Profile</h1>
    <form action="{{ route('profile.save') }}" method="post" id="frm-profile">
        @csrf

        <div class="form-group row">
          <label for="name" class="col-sm-2 col-form-label">Name</label>
          <div class="col-sm-10">
              <input type="text" name="name" class="form-control" id="name" value="{{ old('name') != null ? old('name') : Auth::user()->name }}" placeholder="">
              <p class="help-block">Enter your name. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="url" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
              <input type="text" name="email" class="form-control" id="email" value="{{ old('email') != null ? old('email') : Auth::user()->email }}" placeholder="">
              <p class="help-block">Enter your email.</p>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
