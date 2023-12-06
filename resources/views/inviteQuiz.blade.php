@extends('layouts.app')

@section('title', 'Add Session')

@section('content')
<header class="header">
        <h4>Invite to Quiz:</h4>
    </header>
    @if(!empty($_GET['q_test']))
        <div class="alert alert-primary" role="alert">
            Invite your friends for fun or check on their smarts. They are could be educational.<br>
            Send evaluation quizzes for your future collegues or job applicants.
        </div>
    @endif
    <form action="/doInviteQuiz" method="post" enctype="multipart/form-data" class="">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        @csrf
        <div class="form-group form-control">
            <label for="quiz_id">Your Quizzes: </label>
            <select name="quiz_id" id="quiz_id">
                <option value="">Select...</option>
                @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->id }}">
                        {{ $quiz->quiz_name }}
                    </option>
                @endforeach
            </select>
            
        </div>
        <div class="form-group form-control">
            <label for="category">Name</label>
            <input type="text" name="friend_name" value="{{ old('friend_name') }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="category">Email</label>
            <input type="text" name="email" value="{{ old('email') }}" maxlength="255" />
        </div>
        <input type="submit" class="btn btn-block" value="Send Invite" />

    </form>
@endsection
