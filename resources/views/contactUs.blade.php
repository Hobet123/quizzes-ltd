@extends('layouts.app')

    @section('title', 'Quizzes Ltd - Professional Skills Assessment Tests and Quizzes')
    @section('description', ' Quizzes Ltd offers a wide range of skill assessment tests and quizzes for professionals in various fields including electricians, construction engineers, IT specialists, and more. Evaluate your skills and enhance your career with our comprehensive online quizzes.')
    @section('keywords', 'quizzes, professional skills assessment, electrician tests, construction engineer quizzes, IT specialist evaluations, career enhancement')

    @section('content')


    <div class="container">
        <h4 class="title_h2">
            Contact Us:
<?php
// echo $_SERVER['SERVER_ADDR'];
?>
        </h4>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <p><b> Address:</b></p>
                <p>
                10495 Keele Str. <br>
                Maple ON. L6A 3Y9 <br>
                Canada
                </p>
                <p><b>Phone:</b></p>
                <p>1-416-477-8844</p>
                
            </div>
            <div class="col-md-9">
                <form action="/doContactUs" method="post" enctype="multipart/form-data" id="contactUs">
                @csrf
                <div class="form-group form-control">
                    <label for="email"><b>Your Email</b></label>
                    <input type="text" name="email" value="" />
                </div>

                <div class="form-group form-control">
                    <label for="password"><b>Message</b></label>
                    <textarea name="message" id="" cols="30" rows="10"></textarea>
                </div>      

                <div class="captcha-container">
                    <div class="form-group form-control">
                        <label for="captcha"><b>Please solve</b></label>
                        <div id="captcha" class="captcha-input" style="color: red;"></div>
                        <input type="text" id="userInput" placeholder="Enter the result" />
                    </div>
                </div>  
                <div>

                    <div class="btn btn-block" onclick="checkCaptcha()">Submit</div>

                </div>
                </form>
            </div>
        </div>

    </div>
    <script>
// Generate the initial CAPTCHA
generateCaptcha();
  function generateCaptcha() {
  // Generate two random numbers between 1 and 10
  var num1 = Math.floor(Math.random() * 20) + 1;
  var num2 = Math.floor(Math.random() * 10) + 1;

  // Create the CAPTCHA equation
  var captcha = num1 + " + " + num2 + " = ?";

  // Display the CAPTCHA equation
  document.getElementById("captcha").textContent = captcha;

  // Store the correct answer in a data attribute
  document.getElementById("captcha").setAttribute("data-answer", num1 + num2);
}

function checkCaptcha() {
  // Get the user's input
  var userInput = document.getElementById("userInput").value;

  // Get the correct answer from the data attribute
  var answer = document.getElementById("captcha").getAttribute("data-answer");

  // Check if the user's input matches the correct answer
  if (userInput === answer) {
    // alert("CAPTCHA solved correctly!");
    
    document.getElementById("contactUs").submit();

  } else {
    alert("CAPTCHA solved incorrectly. Please try again.");
  }

  // Generate a new CAPTCHA after checking
  generateCaptcha();

  // Clear the user's input
  document.getElementById("userInput").value = "";
}

// Generate the initial CAPTCHA
generateCaptcha();

</script>
@endsection
