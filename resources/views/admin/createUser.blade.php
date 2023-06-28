@extends('layouts.app_admin')

@section('title', 'Add Session')

@section('content')

        <header class="header">
            <h2>Add User:</h2>
        </header>
        <form action="/admin/doCreateUser" method="post" enctype="multipart/form-data" class="">
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
                    Yes &nbsp;&nbsp;&nbsp; <input type="checkbox" name="is_admin" value="1" @if (old('is_admin') == 1) checked=checked @endif>
                </div>
            </div>
        <input type="submit" class="btn btn-block" value="Add User" />
    </form>
@endsection
