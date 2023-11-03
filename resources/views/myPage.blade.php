@extends('layouts.app')

    @section('title', 'My page')

    @section('content')
        <div class="container d-flex mt-3">
            <div class="me-5"><b><a href="/changePassword">Change Password</a></b></div>
            <div class="me-5"><b><a href="/changeUsername">Change Your Name</a></b></div>
            <div class="me-5"><b><a href="/admin/uploadDuQuiz">Upload Quiz</a></b></div>
            <!-- <div class="me-5"><b><a href="/inviteQuiz">Invite To Quiz</a></b></div> -->
        </div>
        <div><hr></div>
        
        <div class="row">
            @foreach($sessions as $session)
            <div class="col-md-4 mb-4">
                <div class="card m-1" style="">

                    <div class="card-image" style="background-image: url('{{ env('APP_URL') }}/cover_images/{{ $session->cover_image }}');"></div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $session->quiz_name }}</h5>
                        <form action="/quizHome/{{ $session->quiz_id }}" method="GET">
                            
                            <input type="submit" class="btn btn-danger" value="Start Quiz" /><br>
                            @if($session->clarif_flag == 1)
                                <input type="checkbox" name="educational" value="1"> 
                                <span class="educational">Educational (?)</span>
                                <div id="hiddenDiv" class="hiddenDiv">This will activate educational mode. Which will contain explanations for quiz answers and references.</div>
                            @endif
                        </form>
                        <!-- <a href="/quizHome/{{ $session->quiz_id }}" class="btn btn-danger">Start Quiz</a> -->
                    </div>

                </div>
            </div>
            @endforeach

        </div>
        <div class="row">
        @if(count($quizzes))
            <hr>
            <p><h4>Your Quizes:</h4></p>

            <div class="container">
            @foreach ($quizzes as $quiz)  
            <div class="row m-1 p-3 border-bottom">
                <div class="col-6 text-start">
                    # {{ $quiz->id }}: {{ $quiz->quiz_name }} <i>(?ns#:{{ $quiz->quiestions_count }})</i>
                </div>
                <div class="col-4">
                <i>Status: </i>
                    @include('.inc.quiz_sts')
                </div>    
                <div class="col-1">
                    <a href="/admin/editQuiz/{{ $quiz->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                </div>
                <div class="col-1">
                    <a href="/disable_quiz/{{ $quiz->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                </div>
            </div>   
            @endforeach
        </div>

        @endif
        </div>

        @if(!empty($flag))
            <script>
                localStorage.clear();
            </script>
        @endif
<script>
// Get all elements with class "educational"
const educationalSpans = document.querySelectorAll('.educational');

// Add a hover event listener to each educational span
educationalSpans.forEach((span) => {
    span.addEventListener('mouseenter', () => {
        // Find the next sibling element with class "hiddenDiv"
        const hiddenDiv = span.nextElementSibling;

        // Show the hiddenDiv
        if (hiddenDiv) {
            hiddenDiv.style.display = 'block';
        }
    });

    span.addEventListener('mouseleave', () => {
        // Find the next sibling element with class "hiddenDiv"
        const hiddenDiv = span.nextElementSibling;

        // Hide the hiddenDiv when the mouse leaves the span
        if (hiddenDiv) {
            hiddenDiv.style.display = 'none';
        }
    });
});

</script>
         
    @endsection
