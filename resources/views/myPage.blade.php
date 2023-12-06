@extends('layouts.app')

    @section('title', 'My page')

    @section('content')
        <div class="mt-3">
            <b><a href="#myprofile" style="text-decoration: underline;">My profile &nbsp;<i class="fa-solid fa-arrow-down"></i></a></b>
        </div>
        <hr>

        <div class="mt-3">
            <p><b>Welcome to Dashboard:</b></p>
            <p>- Take your quizzes/test to self-evaluate your knowledges</p>
            <p>- Contribute to our website with creating your quizzes and share it with us...</p>
            <p>- Invite your friends, colleges or job applicants to test their skillss</p>
        </div>
        <hr>
            <p><b>My Quizes:</b></p>
            <hr>
        @if(count($sessions))
            <div class="row">

                @foreach($sessions as $session)
                <div class="col-md-3 mb-4">
                    <div class="card m-1" style="">

                        <div class="card-image" style="background-image: url('{{ env('APP_URL') }}/cover_images/{{ $session->cover_image }}');"></div>

                        <div class="card-body">
                            <h5 class="card-title">{{ $session->quiz_name }}</h5>
                            <form action="/quizHome/{{ $session->quiz_id }}" method="GET">
                                
                                <input type="submit" class="btn btn-danger" value="Start Quiz" />&nbsp;&nbsp;&nbsp;
                                @if($session->clarif_flag == 1)
                                    <input type="checkbox" name="educational" value="1"> 
                                    <span class="educational">Learn (?)</span>
                                    <div id="hiddenDiv" class="hiddenDiv">
                                        This will activate educational mode. 
                                        Which will contain explanations for quiz answers and references.
                                    </div>
                                @endif
                            </form>
                            <!-- <a href="/quizHome/{{ $session->quiz_id }}" class="btn btn-danger">Start Quiz</a> -->
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        @endif

        <div class="row">
        @if(count($quizzes))
            <hr>
            <p><b>My Contributed Quizes (editable):</b></p>
            <hr>

            <div class="container">
            @foreach ($quizzes as $quiz)  
            <div class="row m-1 p-3 border-bottom">
                <div class="col-5 text-start">
                    # {{ $quiz->id }}: {{ $quiz->quiz_name }} <i>(questions: {{ $quiz->quiestions_count }})</i>
                </div>
                <div class="col-5">
                    <i>Status: </i>@include('.inc.quiz_sts'), 
                    <span style="color: #1159C6;">{{ ( $quiz->public == 1) ? 'private' : 'public'  }}</span>
                    <span class="public_private">(?)</span>
                    <div id="hiddenDiv" class="hiddenDiv">
A <b>private</b> quiz is your personal quiz that can only be used by people who receive an invitation from you to this quiz.<br>
A <b>public</b> quiz is a quiz that can be used by the users you invite, as well as all users of our website.
                    </div>
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
        <br>
            <p><b>My Profile:</b></p>
        <hr>
        <div class="row" id="myprofile">
            <div class="me-5"><b>- <a href="/changePassword">Change Password</a></b></div>
            <div class="me-5"><b>- <a href="/changeUserEmail">Change Email</a></b></div>
            <div class="me-5"><b>- <a href="/changeUsername">Change Your Name</a></b></div>
            <div class="me-5"><b>- <a href="/admin/uploadDuQuiz?q_test=1">Upload Quiz</a></b></div>
            <div class="me-5"><b>- <a href="/inviteQuiz?q_test=1">Invite To Quiz</a></div>
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
<script>
// Get all elements with class "public_private"
const public_privateSpans = document.querySelectorAll('.public_private');

// Add a hover event listener to each educational span
public_privateSpans.forEach((span) => {
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
