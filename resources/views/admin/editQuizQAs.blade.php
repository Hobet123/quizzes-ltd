@extends('layouts.' . $_SESSION['layout'])

    @section('title', 'Admin Form Login')

    @section('content')
        <header class="header">
            <h3>{{ $quiz->quiz_name }} Questions List:</h3>
        </header>
        <nav class="nav d-flex justify-content-between">
            <div><a href="/admin/editQuiz/{{ $quiz->id }}">Back to Quiz</a></div>
            <div> 
                <a href="/admin/addDuQATo/{{ $quiz->id }}" title="Add Question">Add Question<i class="fa-solid fa-question"></i></a>
            </div>
        </nav>
        <form action="/admin/questionsOrder" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
        <div id="drop_list">
            @foreach ($questions as $question)
                <div class="q_list">
                    <input type="hidden" name="question_{{ $question->id }}" value="{{ $question->id }}">
                    <div style="width: 100%; justify-content: left;">{{ $question->q_name }}</div>
                    <div><p><a href="/admin/editDuQA/{{ $question->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a></p></div>
                    <div style="margin-left: 0px; justify-content: center; padding-left: 10px;">
                        <a href="/admin/deleteQuestion/{{ $quiz->id }}/{{ $question->id }}">
                        <i class="fa-solid fa-trash fa-lg"></i></a></p></div>
                </div>
            @endforeach
        </div>
        <input type="submit" class="btn btn-block" value="Resubmit Order" />
        </form>
        <script>
            $(function() {
                $("#drop_list").sortable();
            });
        </script>
    @endsection
