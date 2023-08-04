@extends('layouts.app')

    @section('title', 'Sign Up Form')

    @section('content')

    <div class="container">
        <h4 class="title_h2">
            Sign Up:
        </h4>
        <form action="/trySignUp" method="post" enctype="multipart/form-data" class="">
            @csrf

            <div class="form-group form-control">
                <label for="username">Your Name</label>
                <input type="text" name="username" value="{{ old('username') }}" required />
            </div>

            <div class="form-group form-control">
                <label for="email">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required />
            </div>
            <div class="form-group form-control">
                <label for="phone">Phone (not required)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" maxlength="15" />
            </div>
            <div class="form-group form-control">

                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required />
                    <a href="#"><span class="password-info"><i class="fa-solid fa-eye fa-lg"></i></span></a>
                </div>
            </div>
            <div class="form-group form-control">  

                <label for="password_confirmation">Confirm Password:</label>
                <div class="password-container">
                    <input type="password" id="password_confirmation" name="password_confirmation" required />
                    <a href="#"><span class="password-info2"><i class="fa-solid fa-eye fa-lg"></i></span></a>
                </div>
            </div>
            <div class="form-group form-control">  

                <label for="password_confirmation">I agree to <a href="/terms">Terms and Conditions:</a></label>
                <div class="password-container">
                    <input type="checkbox" id="agree" name="agree" value="1" />
                </div>
            </div>

            <input type="submit" class="btn btn-block" value="Sign Up" />



        </form>
    </div>

<script>

    const passwordField = document.getElementById('password');
    const passwordInfo = document.querySelector('.password-info');

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

    const passwordField2 = document.getElementById('password_confirmation');
    const passwordInfo2 = document.querySelector('.password-info2');

    passwordInfo2.addEventListener('click', function() {
        if (passwordField2.type === 'password') {
            
            console.log(passwordInfo2);

            passwordField2.type = 'text';
            passwordInfo2.textContent = `hide`;

        } 
        else {

            console.log(passwordInfo2);

            passwordField2.type = 'password';
            passwordInfo2.textContent = 'show';

        }
    });

</script>   
        
@endsection
