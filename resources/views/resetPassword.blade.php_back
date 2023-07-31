@extends('layouts.app')

@section('title', 'User Form Login')

@section('content')
    <h4 class="title_h2">
        Reset Password:
    </h4>
    </header>
    <form action="/doResetPassword" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="email" value="{{ $user->email }}">
        <div class="form-group form-control">
            <label for="username">Password</label>

            <input type="text" name="password" value="" />
        </div>
        <div class="form-group form-control">
            <label for="username">Confirm Password</label>

            <input type="text" name="password_confirmation" value="" />
        </div>        

        <input type="submit" class="btn btn-block" value="Reset Password" id="login" />


    </form>
@endsection
