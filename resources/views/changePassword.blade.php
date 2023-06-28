@extends('layouts.app')

@section('title', 'User Form Login')

@section('content')
    <h4 class="title_h2">
        Change Password:
    </h4>
    <form action="/doChangePassword" method="post" enctype="multipart/form-data" class="">
        @csrf
        <div class="form-group form-control">
            <label for="password">Old Password</label>
            <div class="password-container">
                <input type="password" id="old_password" name="old_password" required />
                <a href="#"><span class="password-info2"><i class="fa-solid fa-eye fa-lg"></i></span></a>
            </div>
        </div>
        <div class="form-group form-control">
            <label for="password">New Password</label>
            <input type="password" name="password" value="" required />
        </div>

        <div class="form-group form-control">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" value="" required />
        </div>        

        <input type="submit" class="btn btn-block" value="Change Password" id="login" />

    </form>
    <script>

        const passwordField = document.getElementById('old_password');
        const passwordInfo = document.querySelector('.password-info2');

        passwordInfo.addEventListener('click', function() {

            if (passwordField.type === 'password') {
                
                console.log(passwordInfo);

                passwordField.type = 'text';
                passwordInfo.textContent = `hide`;

            } 
            else {

                console.log(passwordInfo);

                passwordField.type = 'password';
                passwordInfo.textContent = 'show';

            }
        });
    </script>
@endsection
