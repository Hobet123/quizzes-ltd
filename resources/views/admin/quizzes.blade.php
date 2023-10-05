@extends('layouts.app_admin')

    @section('title', 'Quizzes')

    @section('content')
    <div class="pt-3">
            <h3>Quizzes List:</h3>
        </div>
        <div class="w-100 text-end d-flex flex-row-reverse mb-3">
            <div class="me-3">
                <a href="/admin/uploadDuQuiz"><button class="btn btn-outline-danger">Upload Quiz</button></a>
            </div>
            <div class="me-3">
                <a href="/admin/uploadquiz"><button class="btn btn-outline-danger">Upload XLSX</button></a>
            </div>
            <div class="me-3">
                <a href="/admin/uploadJson"><button class="btn btn-outline-danger">Upload JSON</button></a>
            </div>
        </div>
        @if(!empty($quizzes))
            <div class="container">
            @foreach ($quizzes as $quiz)  
            <div class="row m-1 p-3 border-bottom">
                <div class="col-10 text-start">
                    # {{ $quiz->id }}: {{ $quiz->quiz_name }}
                </div>    
                <div class="col-1">
                    <a href="/admin/editQuiz/{{ $quiz->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                </div>
                <div class="col-1">
                @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)
                    <a href="/delete_quiz/{{ $quiz->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                @endif
                </div>
            </div>   
            @endforeach
            </div>

        @endif

    @endsection
