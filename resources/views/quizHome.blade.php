<?php
    // session_start();
?>

@extends('layouts.app')

@section('title', $quiz->quiz_name.' | Quizzes Ltd')
@section('description', 'Ready to test your skills? Take the on Quizzes Ltd and evaluate your professional expertise. Designed for [target audience], this skill assessment quiz will help you gauge your strengths and identify areas for improvement in your field of expertise.')
@section('keywords', $quiz->meta_keywords)

@section('content')

    @if(!empty($_SESSION['try_quiz']))

        <div class="trial--quiz">
            <span>Trial Quiz</span>
        </div>
    
    @endif
    <h3 class="title_h2">
        {{ $quiz->quiz_name }}
    </h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>{{ $quiz->short_description }}</p>
                <hr>
    <!-- Bundle -->
    
    @if(isset($linked) && !empty($linked))
    <div class="container p-5">
        @foreach($linked as $cur)
            <a href="/quizHome/{{ $cur->id }}"><button class="btn btn-danger">{{ $cur->quiz_name }}</button></a>

        @endforeach
    </div>
    @else

    <!-- Not Bundle -->
    <div class="container">
        <?php
            $y = 1;
        ?>
        @for ($i = 0; $i < $parts; $i++)
            <?php
                $temp = $i*$per_part;
            ?>
            <p>
                @if($i == $parts)
                    <a href="/quizQuestion/{{ $temp }}"><button class="btn btn-danger">Start Quiz</button></a>
                @else
                    <a href="/quizQuestion/{{ $temp }}"><button class="btn btn-danger">Start Part {{ $y }}</button></a>
                @endif
                
            </p>
            <?php
                $y++;
            ?>
        @endfor
    </div>
    @endif
    <!-- END -->
                <p><h4>Description</h4></p>
                <hr>
                <p><?php echo $quiz->quiz_description; ?></p>
            </div>
            <div class="col-md-6">
                <img src="/cover_images/{{ $quiz->cover_image }}" alt="" width="90%">
            </div>
        </div>
    </div>



    </div>

@endsection
