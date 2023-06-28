@extends('layouts.app')

@section('title', 'User Form Login')

@section('content')
    <h4 class="title_h2">
        User Login:
    </h4>
    <div class="conteiner">
        <form action="/usertrylogin" method="post" enctype="multipart/form-data" class="">
            @csrf
            <div class="form-group form-control">
                <label for="email">Email</label>
                <input type="text" name="email" value="" />
            </div>
            <div class="form-group form-control">
                <label for="password">Password</label>
                <input type="password" name="password" value="" />
            </div>

            <input type="submit" class="btn btn-block" value="Login" id="login" />

        </form>
    </div>
    <div class="d-flex justify-content-center p-3">
        <p ><a href="/forgotPassword">Forgot Password???</a></p>
    </div>

@endsection
