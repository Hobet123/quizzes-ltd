@extends('layouts.app_admin')

@section('title', 'Add Session')

@section('content')


    <header class="header">
            <h2>Edit User:</h2>
        </header>
        <form action="/admin/doEditUser" method="post" enctype="multipart/form-data" class="">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
        <div class="form-group form-control">
            <label for="username">Username</label>
            <input type="text" name="username" value="{{ $user->username }}" />
        </div>
        <div class="form-group form-control">
            <label for="phone">Phone</label>
            <input type="text" name="phone" value="{{ $user->phone }}" />
        </div>
        <div class="form-group form-control">
            <label for="password">Password</label>
            <input type="password" name="password" value="{{ $user->password }}" />
        </div>
        <div class="form-group form-control">
            <label for="Email">Email</label>
            <input type="text" name="email" value="{{ $user->email }}" />
        </div>
        @if(!empty($_SESSION['super_admin']) && $_SESSION['super_admin'] == 2)
        <div class="form-group p-2">
            <div>Is the user admin:</div>
                <div class="radio_choice p-3">
                    Yes  &nbsp;&nbsp;&nbsp; <input type="checkbox" name="is_admin" value="1" @if ($user->is_admin === 1) checked=checked @endif>
                </div>

        </div>
        @endif
        <div class="form-group p-3 m-3">   
            <input type="submit" class="btn btn-block" value="Edit User" />
        </div>
        <div class="d-flex justify-content-center">
            <a href="/admin/sendUserEmail/{{ $user->id }}">Send Email to User with Username/Password</a>
        </div>
    </form>
@endsection
