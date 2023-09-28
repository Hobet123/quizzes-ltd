@extends('layouts.app_admin')

    @section('title', 'Admin Form Login')

    @section('content')
        <header class="header text-center p-3">
            <div class="mt-20 p-20">
            <h2><b>Dashboard</b></h2>
            </div>
        </header>
        <!-- <div class="text-start">
            <h5><b>Quizes List:</b></h5>
        </div> -->
            <!-- @if(!empty($data[0]))
            <div class="container">
                @foreach ($data[0] as $quiz)
                <div class="row m-1 p-3 border-bottom">
                    <div class="col-10 text-start">
                        # {{ $quiz->id }}: {{ $quiz->quiz_name }}
                    </div>    
                    <div class="col-1">
                        <a href="admin/editQuiz/{{ $quiz->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    </div>
                    <div class="col-1">
                        <a href="/delete_quiz/{{ $quiz->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif -->
        <div class="text-start">
            <br>
            <h5><b>Users List:</b></h5>
        </div>
            @if(!empty($data[1]))
            <div class="container">
                @foreach ($data[1] as $user)
                <div class="row m-1 p-3 border-bottom">
                    <div class="col-10 text-start">
                    @if($user->is_admin == 1)
                        <i class="fa-sharp fa-solid fa-user-secret"></i>&nbsp;
                        @endif
                    {{ $user->username }} ({{ $user->email }})
                    </div>
                    <div class="col-1">
                        <a href="/admin/editUser/{{ $user->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    </div>
                    <div class="col-1">
                        <a href="/admin/deleteUser/{{ $user->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        <div class="text-start">
            <!-- <h4>Sessions List:</h4> -->
        </div>
        <?php
            use \App\Http\Controllers\SessionController;  
        ?>
        
            @if(!empty($data[2]))
            <div class="container">
                
            </div>
            @endif

    @endsection
