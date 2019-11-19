@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Passwords</h2>

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <table class="table table-bordered" id="tb-list">
            <thead class="thead-light">
                <tr>
                    <th scope="col" width="2">No</th>
                    <th scope="col">Name</th>
                    <th scope="col" class="w-25">Action</th>
                </tr>
            </thead>
            <tbody>
                @php ($no = 0)
                @foreach ($passwords as $password)
                    @php ($no++)
                    <tr class="table-secondary">
                        <td>{{ $no }}</td>
                        <td>{{ $password->name }}</td>
                        <td>
                            <form action="{{ route('pass.destroy', $password->id) }}" method="POST" class="frm-delete">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('pass.edit', $password->id)}}" class="btn btn-sm btn-info btn-edit">Edit</a>
                                <button type="submit" class="btn btn-sm btn-danger btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('pass.create') }}" class="btn btn-primary" id="btn-add-new">Add new password</a>
    </div>
@endsection
