@extends('layouts.app')

    @section('title', 'Sign Up Form')

    @section('content')

    <div class="container">
        <div id="error-messages"></div>
        <h4 class="title_h2">
            Sign Up:
        </h4>
        <form id="signup-form" action="/doSignUp" method="post" enctype="multipart/form-data" class="">
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
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" maxlength="15" placeholder="(555) 555-5555" />
            </div>

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

            <div class="form-group form-control">  
                <label for="agree">I agree to <a href="/terms">Terms and Conditions:</a></label>
                <div class="password-container">
                    <input type="checkbox" id="agree" name="agree" value="1" />
                </div>
            </div>
            <!-- <button type="submit" class="btn btn-block">Sign Up</button> -->
            <input type="submit" class="btn btn-block" value="Sign Up" />

        </form>
    </div>

@endsection
