@extends('layouts.app')

@section('title', 'Add Session')

@section('content')
    @if(!empty($_SESSION['try_quiz']))

    <div class="trial--quiz">
        <span>Trial Quiz</span>
    </div>

    @endif
    <header class="header">
        <h1>Final!</h1>
    </header>
    <div class="quiz-final">
        <div style="margin-bottom: 10px;"><h3>Congratulations!</h3></div>
        <div><h3>You've answered Correct</h3></div>
        <div><h1 style="color: #198754;">{{ $_SESSION['correct'] }}</h1></div>
        <div><h3>Out of</h3></div>
        <div><h1 style="color: #BB2D3B;">{{ $_SESSION['total_qns'] }}</h1></div>
        <div><h3>Questions</h3></div>
        <div>
@if(!empty($_SESSION['try_quiz']))
<?php
    // unset($_SESSION['user']);
    // unset($_SESSION['user_id']);
    unset($_SESSION['try_quiz']);
    unset($_SESSION['quiz_id']);
?>
    <a href="/quizes"><button class="btn btn-outline-danger">Go back to Quizzes!</button></a>
    <br>

@else
    <a href="/myPage"><button class="btn btn-outline-danger">Go back to your Quizzes!</button></a>
@endif 
<?php
    unset($_SESSION['quiz_id']);
?>
        
        </div>
    </div>

@endsection
