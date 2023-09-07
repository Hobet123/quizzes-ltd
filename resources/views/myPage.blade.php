@extends('layouts.app')

    @section('title', 'My page')

    @section('content')
        <div class="container d-flex mt-3">
            <div class="me-5"><b></datagrid><a href="/changePassword">Change Password</a></b></div>
            <div><b></datagrid><a href="/changeUsername">Change Your Name</a></b></div>
            
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
                            <input type="checkbox" name="educational" value="1"> 
                            <span class="educational">Educational</span>
                            <div id="hiddenDiv" class="hiddenDiv">This will activate educational mode. Which will contain explanations and references.</div>
                        </form>
                        <!-- <a href="/quizHome/{{ $session->quiz_id }}" class="btn btn-danger">Start Quiz</a> -->
                    </div>

                </div>
            </div>
            @endforeach

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
