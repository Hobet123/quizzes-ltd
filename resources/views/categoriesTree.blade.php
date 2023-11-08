@extends('layouts.app')

    @section('title', 'Professional Skills Assessment Tests and Quizzes - Quizzes Ltd')
    @section('description', 'Discover a comprehensive list of professional skills assessment tests and quizzes offered by Quizzes Ltd. Choose from a variety of assessments tailored for electricians, construction engineers, IT specialists, and more. Evaluate your expertise and excel in your profession.')
    @section('keywords', 'professional skills assessment tests, quizzes, electrician assessments, construction engineer evaluations, IT specialist quizzes, career advancement')

    @section('content')

    <!-- <div class="container"> -->
    <div class="row">

    <div class="container">

        <div><h4 class="title_h2" style="">Tree Category</h4></div>
        <div class="p-2">
        @foreach($query as $cur)
            @if($cur->parent_id == 0)
                <div class="cat_tree parent_cat m-1"><a href="/category/{{ $cur->id }}">{{ $cur->cat_name }}</a></div>
                @foreach($query as $cur_sub)
                    @if($cur->id == $cur_sub->parent_id)
                        <div class="cat_tree m-3"> - &nbsp;<a href="/category/{{ $cur_sub->id }}">{{ $cur_sub->cat_name }}</a></div>
                    @endif
                @endforeach
            @endif
        @endforeach
        </div>

    </div>

    @endsection
