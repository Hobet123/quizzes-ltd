@extends('layouts.app')

@section('title', 'Add Session')

@section('content')
    @if(!empty($_SESSION['try_quiz']))

    <div class="trial--quiz">
        <span>Trial Quiz</span>
    </div>

    @endif
    <header class="header">
        <h3>Question {{ $_SESSION['qns_count'] }} of {{ $_SESSION['total_qns'] }}:</h3>
    </header>
    <form action="/quizAnswer/" method="GET">
        <input type="hidden" name="qn_index" value="{{ $qn_index }}">
        @csrf
        <div class="desc m-3">
            <div class="">
                {{ $question->q_name }}
                <?php
                    //echo "<br><span style='color: lightgray>'<br>#qid: ".$question->id."<br>";
                ?>
            </div>
            @if ($question->q_image != null)

                <div class="">
                    <?php
                        $temp_img = str_replace("image", "", $question->q_image);
                    ?>
                    <img src="/questions_images/q_{{ $_SESSION['quiz_id'] }}/sample_images/{{ $temp_img }}" height="200" />
                </div>
            @endif
        </div>
        <div class="form-group radio-answers" >
            <div><b>Select Answer:</b></div>
            @foreach ($answers as $answer)
                <div class="radio_choice m-1" style="">
                    <div><input type="radio" class="form-check-input" name="answer_id" value="{{ $answer->id }}"></div>
                    <div>{{ $answer->a_name }}</div>
                </div>
            @endforeach

        </div>

        <input type="submit" class="btn btn-block" style="margin: 4px;" value="Submit Answer" />


    </form>
@endsection
