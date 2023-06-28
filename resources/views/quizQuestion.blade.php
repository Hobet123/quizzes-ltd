@extends('layouts.app')

@section('title', 'Add Session')

@section('content')
    <header class="header">
        <h1>Question {{ $_SESSION['qns_count'] }} of {{ $_SESSION['total_qns'] }}:</h1>
    </header>
    <form action="/quizAnswer/" method="GET">
        <input type="hidden" name="qn_index" value="{{ $qn_index }}">
        @csrf
        <div class="desc">
            <div class="">
                {{ $question->q_name }}
                <?php
                    echo "<br><span style='color: lightgray>'<br>#qid: ".$question->id."<br>";
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
                <div class="radio_choice">
                    <div><input type="radio" name="answer_id" value="{{ $answer->id }}"></div>
                    <div>{{ $answer->a_name }}</div>
                </div>
            @endforeach

        </div>

        <input type="submit" class="btn btn-block" value="Submit Answer" />


    </form>
@endsection
