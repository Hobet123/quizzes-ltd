@extends('layouts.app')

@section('title', 'Add Session')

@section('content')
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
    </div>

@endsection
