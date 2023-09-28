@extends('layouts.app')

    @section('title', 'Professional Skills Assessment Tests and Quizzes - Quizzes Ltd')
    @section('description', 'Discover a comprehensive list of professional skills assessment tests and quizzes offered by Quizzes Ltd. Choose from a variety of assessments tailored for electricians, construction engineers, IT specialists, and more. Evaluate your expertise and excel in your profession.')
    @section('keywords', 'professional skills assessment tests, quizzes, electrician assessments, construction engineer evaluations, IT specialist quizzes, career advancement')

    @section('content')

    <!-- <div class="container"> -->
    <div class="row">

    <div class="container">
            <div class="row ps-3">
                <div class="d-flex justify-content-between">
                    <div><h4 class="title_h2" style="">Quizzes/Tests:</h4></div>
                    @if(isset($count))
                    <div>
                        <i>
                        <b>{{ $count }}</b>: Results
                        @if($count == 0)
                        Please consider other quizzes.
                        @endif
                        </i>
                    </div>
                    @endif
                    @if(isset($category))
                    <div>
                        <i>
                            Category:
                        </i>
                        <b>{{ $category->cat_name }}</b>
                    </div>
                    @endif
                </div>
                @foreach($quizes as $quiz)
                <div class="col-md-4 mb-4">
                    <div class="card m-1" style="">

                        <div class="card-image" style="background-image: url('{{ env('APP_URL') }}/cover_images/{{ $quiz->cover_image }}');"></div>

                        <div class="card-body" style="">
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

    </div>
    <!-- </div> -->
        
    @endsection
