@extends('layouts.app')

@section('title', 'User Form Login')

@section('content')
    <h4 class="title_h2">
        Forgot Password:
    </h4>
    </header>
    <form action="/resetPassword" method="post" enctype="multipart/form-data" class="">
        @csrf
        <div class="form-group form-control">
            <label for="username">Email</label>

            <input type="text" name="email" value="{{ old('email') }}" />
        </div>

        <input type="submit" class="btn btn-block" value="Reset Password" id="login" />


    </form>
@endsection
