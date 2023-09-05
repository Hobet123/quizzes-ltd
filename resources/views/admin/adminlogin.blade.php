@extends('layouts.app_BACK')

@section('title', 'Admin Form Login 2')

@section('content')
<div class="" style="background-color: white; margin: auto;  width: 80%; border: 3px solid #73AD21; padding: 10px;">
    <header class="header">
        <h3>Admin Login:</h3>
    </header>
    <form action="/admintrylogin" method="post" enctype="multipart/form-data" class="">
        @csrf
        <div class="form-group form-control">
            <label for="username">Username</label>
            <input type="text" name="username" value="" />
        </div>
        <div class="form-group form-control">
            <label for="password">Password</label>
            <input type="text" name="password" value="" />
        </div>

        <input type="submit" style="margin: 20px; padding: 10px; background-color: red; color: white;" value="Login" />

    </form>
</div>
@endsection
