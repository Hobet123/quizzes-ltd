@extends('layouts.app')

    @section('title',  $home->title)
    @section('description', $home->meta_keywords)
    @section('keywords', $home->meta_description)

    <!-- @section('title', 'Quizzes Ltd - Professional Skills Assessment Tests and Quizzes')
    @section('description', 'Quizzes Ltd offers a wide range of skill assessment tests and quizzes for professionals in various fields including electricians, construction engineers, IT specialists, and more. Evaluate your skills and enhance your career with our comprehensive online quizzes.')
    @section('keywords', 'quizzes, professional skills assessment, electrician tests, construction engineer quizzes, IT specialist evaluations, career enhancement')
 -->

    @section('content')

        <div class="container">


            <h4 >
                <b>Welcome to Quizzes ssl hello!</b>
            </h4>
            <p>
                <?php echo $home->main_text; ?>
                <!-- Multiple quizzes throughout the course are more effective than a single eLearning assessment at the end. 
                Test practical skills in the form of informal quizzes and games to engage the corporate learner. 
                Use a timed visual quiz or a timed mini game.
            </p>
            <p>
                Employees enjoy the competitive element of a timed challenge. 
                Online quizzes and games are effective in testing practical skills because they 
                help learners evaluate their own performance and feel a sense of achievement. -->
            </p>

        </div>
        <div class="container">
            <div class="row ps-3">
                <!-- <h4 class="title_h2" style="color: #52C795;">Featured:</h4> -->
                @foreach($quizes as $quiz)
                <div class="col-md-4 mb-4">
                    <div class="card m-1" style="">

                        <div class="card-image" style="background-image: url('{{ env('APP_URL') }}/cover_images/{{ $quiz->cover_image }}');"></div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $quiz->quiz_name }}</h5>

                            <p>&nbsp;
                            @if(isset($quiz->categories) && !empty($quiz->categories))
                                <b style="color: gray;">Category:</b> <i><?php echo $quiz->categories; ?></i>
                            @endif
                            </p>

                            <a href="/quizDetails/{{ $quiz->sef_url }}" class="btn btn-danger">Details</a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
        
    @endsection
