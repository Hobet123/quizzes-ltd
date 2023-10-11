@extends('layouts.app')

@section('title', 'User Form Login')

@section('content')
    <h4 class="title_h2">
        Set Password:
    </h4>
    </header>
    <form action="/doResetPassword" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="email" value="{{ $user->email }}">
        <div class="form-group form-control">
            <label for="password">Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required />
                <a href="#"><span class="password-info"><i class="fa-solid fa-eye fa-lg"></i></a>
                <!-- <i class="fa-solid fa-eye fa-lg"></i></span> -->
                <div class="px-3"><small><i>
Password must be between 8 -16 characters and contain at least one Uppercase Letter, one digit, and one special character (ex.: #$!&$)..</i></small></div>
            </div>
        </div>
        <div class="form-group form-control">  
            <label for="password_confirmation">Confirm Password:</label>
            <div class="password-container">
                <input type="password" id="password_confirmation" name="password_confirmation" required />
                <a href="#"><span class="password-info2"><i class="fa-solid fa-eye fa-lg"></i></span></a>
                <!-- <i class="fa-solid fa-eye fa-lg"></i> -->
            </div>
        </div>    

        <input type="submit" class="btn btn-block" value="Reset Password" id="login" />


    </form>
@endsection
