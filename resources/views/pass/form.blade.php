@extends('layouts.app')

@section('content')
<div class="container">
    <h1>@if ($action == 'create') Add new @elseif($action == 'edit') Edit @endif password</h1>
    <form action="@if ($action == 'create') {{ route('pass.store') }} @elseif ($action == 'edit') {{ route('pass.update', $password->id) }} @endif" method="post" id="frm-password">
        @csrf

        @if ($action == 'edit')
            @method('PUT')
        @endif

        <div class="form-group row">
          <label for="name" class="col-sm-2 col-form-label">Name</label>
          <div class="col-sm-10">
              <input type="text" name="name" class="form-control" id="name" value="{{ old('name') != null ? old('name') : (isset($password) ? $password->name : '') }}" placeholder="">
              <p class="help-block">Enter password's name. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="url" class="col-sm-2 col-form-label">URL</label>
          <div class="col-sm-10">
              <input type="text" name="url" class="form-control" id="url" value="{{ old('url') != null ? old('url') : (isset($password) ? $password->url : '') }}" placeholder="">
              <p class="help-block">Enter URL.</p>
          </div>
        </div>

        <div class="form-group row">
          <label for="login" class="col-sm-2 col-form-label">Login</label>
          <div class="col-sm-10">
              <input type="text" name="login" class="form-control" id="login" value="{{ old('login') != null ? old('login') : (isset($password) ? $password->login : '') }}" placeholder="">
              <p class="help-block">Enter login, <i>e.g. admin@somedomain.com</i>. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
              <input type="password" name="password" class="form-control" id="password" value="{{ old('password') != null ? old('password') : (isset($password) ? $password->password : '') }}" placeholder="">
              <p class="help-block">Enter password. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="notes" class="col-sm-2 col-form-label">Notes</label>
          <div class="col-sm-10">
              <textarea name="notes" class="form-control" id="notes" rows="4">{{ old('notes') != null ? old('notes') : (isset($password) ? $password->notes : '') }}</textarea>
              <p class="help-block">Enter notes.</p>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
