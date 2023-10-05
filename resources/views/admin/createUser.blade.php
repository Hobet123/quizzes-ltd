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
            <label for="password">Password:</label>
            <div class="password-container">
                <input type="text" id="password" name="password" required />
                <a href="#"><span class="password-info"><i class="fa-solid fa-eye fa-lg"></i></span></a>
                <div class="px-3"><small><i>
Password must be between 8 -16 characters and contain at least one Uppercase Letter, one digit, and one special character (ex.: #$!&$)..</i></small></div>
            </div>
        </div>
        <div class="form-group form-control">  
            <label for="password_confirmation">Confirm Password:</label>
            <div class="password-container">
                <input type="text" id="password_confirmation" name="password_confirmation" required />
                <a href="#"><span class="password-info2"><i class="fa-solid fa-eye fa-lg"></i></span></a>
            </div>
        </div>
        <div class="form-group form-control">
            <label for="Email">Email</label>
            <input type="text" name="email" value="{{ old('email') }}" />
        </div>
        <div class="form-group form-control">
            <label for="Phone">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" />
        </div>
        <div class="form-group mb-3">
            <div>Is the user admin:</div>
                <div class="radio_choice p-3">
                    Yes &nbsp;&nbsp;&nbsp; <input type="checkbox" name="is_admin" value="1" @if (old('is_admin') == 1) checked=checked @endif>
                </div>
        </div>
        <input type="submit" class="btn btn-block" value="Add User" />
    </form>
@endsection
