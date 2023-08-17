@extends('layouts.app')

    @section('title', $quiz->quiz_name . ' | Quizzes Ltd')
    @section('description', 'Ready to test your skills? Take the on Quizzes Ltd and evaluate your professional expertise. Designed for [target audience], this skill assessment quiz will help you gauge your strengths and identify areas for improvement in your field of expertise.')
    @section('keywords', $quiz->meta_keywords)

    @section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-8">
                <div class="mt-3"><h4>{{ $quiz->quiz_name }}</h4></div>
                <hr>
                <div class="">
                    <div class="mb-3">
                    <?php echo $quiz->short_description; ?>
                    </div>
                    
                    <div class="mb-1">
                        <b>Price:</b> <span style="color: red;"><b>${{ $quiz->quiz_price }}</b></span>
                        <br><br>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" onclick="addToCart('{{ $quiz->quiz_name }}', {{ $quiz->id }}, {{ $quiz->quiz_price }}, '{{ $quiz->cover_image }}')">Add to Cart</button>
                    </div>

                </div>
                <hr>
                <div>
                    <b style="color: gray;">Category:</b> <i>{{ $quiz->category }}</i>
                </div> 
                <hr>
                <div class="flex">
                    @if($linked == 0)
                        <a href="/tryQuiz/{{ $quiz->id }}"><button type="button" class="btn btn-info" style="">Example Quiz</button></a>                         
                    @else
                        @foreach($linked as $cur)
                        <div class="mb-1"><a href="/tryQuiz/{{ $cur->id }}" class="mb-3"><button type="button" class="btn btn-info" style=""> {{ $cur->quiz_name }} Quiz Example</button></a></div>                         
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-image" style="background-image: url('{{ env('APP_URL') }}/cover_images/{{ $quiz->cover_image }}');"></div>
            </div>


        </div>
        <div class="row p-3">
                <h5>Description</h5>
                <hr>
                <?php echo $quiz->quiz_description; ?>


        </div>  

    </div>

    
    @endsection
