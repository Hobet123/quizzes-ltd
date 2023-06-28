@extends('layouts.app_admin')

@section('title', 'Add Session')

@section('content')
    <header class="header">
        <h1>Add Session:</h1>
    </header>
    <form action="/admin/doAddSession" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />

        <div class="form-group form-control">
            <label for="username">Select Username</label>
            <select name="user_id" id="">
                <option value="">Select...</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->username }}</option>
                @endforeach 
            </select>
        </div>
        <div class="form-group form-control">
            <div>Select Test</div>
            <select name="quiz_id" id="">
                <option value="">Select...</option>
                @foreach($quizes as $quiz)
                <option value="{{ $quiz->id }}">{{ $quiz->quiz_name }} ({{ $quiz->id }})</option>
                @endforeach 
            </select>
        </div>

        <input type="submit" class="btn btn-block" value="Add Session" />

    </form>
@endsection
