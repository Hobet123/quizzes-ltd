@extends('layouts.app')

@section('title', 'Add Session')

@section('content')

    @if(isset($user))
        <header class="header">
            <h2>Add User:</h2>
        </header>
        <form action="/admin/doCreateUser" method="post" enctype="multipart/form-data" class="">
    @else
    <header class="header">
            <h2>Edit User:</h2>
        </header>
        <form action="/admin/doEDitUser" method="post" enctype="multipart/form-data" class="">
        <input type="hidden" name="user_id" value="{{ old('id') }}">
    @endif
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
        <div class="form-group form-control">
            <label for="username">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" />
        </div>
        <div class="form-group form-control">
            <label for="password">Password</label>
            <input type="text" name="password" value="{{ old('password') }}" />
        </div>
        <div class="form-group form-control">
            <label for="Email">Email</label>
            <input type="text" name="email" value="{{ old('email') }}" />
        </div>
        <div class="form-group">
            <div>Is the user admin:</div>
                <div class="radio_choice">
                    Yes <input type="checkbox" name="is_admin" value="{{ old('is_admin') }}">
                </div>
            </div>
        <input type="submit" class="btn btn-block" value="Add User" />
    </form>
@endsection
