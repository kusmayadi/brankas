@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add new password</h1>

    <form action="{{ route('pass.store') }}" method="post">
        @csrf

        <div class="form-group row">
          <label for="name" class="col-sm-2 col-form-label">Name</label>
          <div class="col-sm-10">
              <input type="text" name="name" class="form-control" id="name" placeholder="">
              <p class="help-block">Enter password's name. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="url" class="col-sm-2 col-form-label">URL</label>
          <div class="col-sm-10">
              <input type="text" name="url" class="form-control" id="url" placeholder="">
              <p class="help-block">Enter URL.</p>
          </div>
        </div>

        <div class="form-group row">
          <label for="login" class="col-sm-2 col-form-label">Login</label>
          <div class="col-sm-10">
              <input type="text" name="login" class="form-control" id="login" placeholder="">
              <p class="help-block">Enter login, <i>e.g. admin@somedomain.com</i>. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="password" class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
              <input type="password" name="password" class="form-control" id="password" placeholder="">
              <p class="help-block">Enter password. <strong><i>It is required</i></strong></p>
          </div>
        </div>

        <div class="form-group row">
          <label for="notes" class="col-sm-2 col-form-label">Notes</label>
          <div class="col-sm-10">
              <textarea name="notes" class="form-control" id="notes" rows="4"></textarea>
              <p class="help-block">Enter notes.</p>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
