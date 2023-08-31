@extends('layouts.app_admin')

    @section('title', 'Sessions')

    @section('content')
        <div class="text-start mt-3">
            <h4>Sessions List:</h4>
        </div>
        <div class="w-100 text-end">
            <a href="/admin/addSession"><button class="btn btn-outline-danger mb-3">Add Session</button></a>
        </div>
    <?php
        use \App\Http\Controllers\SessionController;  
    ?>
        
        @if(!empty($sessions))
        <div class="container">

            
            @foreach ($sessions as $session)

            <?php

                $quiz = SessionController::getQuizName($session->quiz_id);
                $user = SessionController::getUserName($session->user_id);

            ?>
                @if(!empty($quiz) && !empty($user))
                    <div class="row m-1">
                        <div class="col-10 text-start">
                            {{ $quiz->quiz_name }}
                        </div>
                        <div class="col-1">
                            {{ $user->username }}
                        </div>
                        <div class="col-1">
                            <a href="/admin/deleteSession/{{ $session->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                        </div>
                    </div>
                @endif

            @endforeach
        </div>
        @endif

    @endsection
