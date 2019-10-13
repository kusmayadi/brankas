@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Passwords</h1>

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <table class="table table-table-bordered" id="tb-list">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @php ($no = 0)
                @foreach ($passwords as $password)
                    @php ($no++)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $password->name }}</td>
                        <td><a href="{{ route('pass.edit', $password->id)}}" class="btn btn-sm btn-info btn-edit">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
