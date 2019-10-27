@extends('layouts.app')

@section('content')
<div class="container">
    <h1>@if ($action == 'create') Add new @elseif($action == 'edit') Edit @endif server</h1>
    <form action="@if ($action == 'create') {{ route('server.store') }} @elseif ($action == 'edit') {{ route('server.update', $server->id) }} @endif" method="post" id="frm-server">
        @csrf

        @if ($action == 'edit')
            @method('PUT')
        @endif

        <div class="form-group row">
          <label for="name" class="col-sm-2 col-form-label">Name</label>
          <div class="col-sm-10">
              <input type="text" name="name" class="form-control" id="name" value="{{ old('name') != null ? old('name') : (isset($server) ? $server->name : '') }}" placeholder="">
              <p class="help-block">Enter server's name. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="url" class="col-sm-2 col-form-label">URL/Hostname/IP Address</label>
          <div class="col-sm-10">
              <input type="text" name="url" class="form-control" id="url" value="{{ old('url') != null ? old('url') : (isset($server) ? $server->url : '') }}" placeholder="">
              <p class="help-block">Enter URL or Hostname or IP Address.</p>
          </div>
        </div>

        <div class="form-group row">
          <label for="login" class="col-sm-2 col-form-label">Username</label>
          <div class="col-sm-10">
              <input type="text" name="username" class="form-control" id="username" value="{{ old('username') != null ? old('username') : (isset($server) ? $server->username : '') }}" placeholder="">
              <p class="help-block">Enter username. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
              <input type="password" name="password" class="form-control" id="password" value="{{ old('password') != null ? old('password') : (isset($server) ? $server->password : '') }}" placeholder="">
              <p class="help-block">Enter password. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="url" class="col-sm-2 col-form-label">Console URL</label>
          <div class="col-sm-10">
              <input type="text" name="console_url" class="form-control" id="console_url" value="{{ old('console_url') != null ? old('console_url') : (isset($server) ? $server->console_url : '') }}" placeholder="">
              <p class="help-block">Enter Console URL.</p>
          </div>
        </div>

        <div class="form-group row">
          <label for="login" class="col-sm-2 col-form-label">Console Username</label>
          <div class="col-sm-10">
              <input type="text" name="console_username" class="form-control" id="console_username" value="{{ old('console_username') != null ? old('console_username') : (isset($server) ? $server->console_username : '') }}" placeholder="">
              <p class="help-block">Enter console username.</p>
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-sm-2 col-form-label">Console Password</label>
          <div class="col-sm-10">
              <input type="password" name="console_password" class="form-control" id="console_password" value="{{ old('console_password') != null ? old('console_password') : (isset($server) ? $server->console_password : '') }}" placeholder="">
              <p class="help-block">Enter password.</p>
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-sm-2 col-form-label">Hostname</label>
          <div class="col-sm-10">
              <input type="text" name="hostname" class="form-control" id="hostname" value="{{ old('hostname') != null ? old('hostname') : (isset($server) ? $server->hostname : '') }}" placeholder="">
              <p class="help-block">Enter hostname.</p>
          </div>
        </div>

        <div class="form-group row">
          <label for="notes" class="col-sm-2 col-form-label">Notes</label>
          <div class="col-sm-10">
              <textarea name="notes" class="form-control" id="notes" rows="4">{{ old('notes') != null ? old('notes') : (isset($server) ? $server->notes : '') }}</textarea>
              <p class="help-block">Enter notes.</p>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
