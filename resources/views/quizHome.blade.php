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
                <p><?php echo $quiz->short_description; ?></p>
                <hr>
    <!-- Bundle -->
    
    @if(isset($linked) && !empty($linked))
    <div class="container p-5">
        @foreach($linked as $cur)

            @if($cur->clarif_flag == 0)
            <div>
                <a href="/quizHome/{{ $cur->id }}?educational={{ $cur->clarif_flag }}"><button class="btn btn-danger">{{ $cur->quiz_name }}</button></a>
            </div>
            <hr>
            @else
            <div>
                <form action="/quizHome/{{ $cur->id }}" method="GET">   
                    <input type="submit" class="btn btn-danger" value="{{ $cur->quiz_name }}" /><br>
                    @if($cur->clarif_flag == 1)
                        <input type="checkbox" name="educational" value="1"> 
                        <span class="educational">Educational (?)</span>
                        <div id="hiddenDiv" class="hiddenDiv">
                            This will activate educational mode. Which will contain explanations for quiz answers and references.
                        </div>
                    @endif
                </form>
            </div>
            <hr>
            @endif
        @endforeach
    </div>
    @else

    <!-- Not Bundle -->
    <div class="container">
        <?php
            $y = 1;

            // echo $_SESSION['educational'];
            // echo $parts;
        ?>
        @for ($i = 0; $i < $parts; $i++)
            <?php
                $temp = $i*$per_part;
            ?>
            <p>
                @if($parts == 1)
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
