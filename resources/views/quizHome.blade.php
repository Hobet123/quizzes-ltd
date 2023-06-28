@extends('layouts.app')

@section('title', 'Home Quiz')

@section('content')

    <h3 class="title_h2">
        {{ $quiz->quiz_name }}
    </h3>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p>{{ $quiz->short_description }}</p>
                <hr>
                <p><h4>Description</h4></p>
                <hr>
                <p>{{ $quiz->quiz_description }}</p>
            </div>
            <div class="col-md-6">
                <img src="/cover_images/{{ $quiz->cover_image }}" alt="" width="90%">
            </div>
        </div>
    </div>
    <div class="container">
        <?php
            $y = 1;
        ?>
        @for ($i = 0; $i < $parts; $i++)
            <?php
                $temp = $i*$per_part;
            ?>
            <p>
                <a href="/quizQuestion/{{ $temp }}"><button class="btn btn-danger">Part {{ $y }}</button></a>
                
            </p>
            <?php
                $y++;
            ?>
        @endfor
    </div>


    </div>

@endsection
