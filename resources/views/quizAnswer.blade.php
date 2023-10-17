

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
    <?php
        if($final_flag == 1){
            $form_url = 'quizFinal';
            $btn_test = "Finish";
        }
        else{
            $form_url = 'quizQuestion';
            $btn_test = "Next Question";
        }

        ++$qn_index;
    ?>
    <form action="/{{ $form_url }}/{{ $qn_index }}" method="POST" enctype="multipart/form-data" class="">
        <input type="hidden" name="qn_index" value="{{ $qn_index }}">
        @csrf
        <div class="desc p-3">
            <div class="">
                {{ $question->q_name }}
                <?php
                // dd($question);
                ?>
            </div>
            @if ($question->q_image != null)
            <div class="">
                <?php
                    $temp_img = str_replace("image", "", $question->q_image);
                ?>
                <!-- <img src="/questions_images/q_{{ $_SESSION['quiz_id'] }}/sample_images/{{ $temp_img }}" height="100" /> -->
                @if(file_exists("/var/www/laravel/public/questions_images/q_".$_SESSION['quiz_id']."/sample_images/".$temp_img))
                        <!-- /questions_images/q_15/sample_images/ques_511.jpg -->
                    <img src="/questions_images/q_{{ $_SESSION['quiz_id'] }}/sample_images/{{ $temp_img }}" height="200" />
                @endif
            </div>
            @endif
        </div>
        <div class="answer_block">
            @if($correct_a_flag == 1)
                <div class="correct">CORRECT ANSWER!</div>
            @else
                <div class="wrong">You Gave Wrong Answer!</div>
                <div class="answer">
                    <p>Correct Answer Would Be:</p>
                    <div style="padding-left: 5px;">
                        <i>
                        {{ $correct_a->a_name }}
                        </i>
                    </div>
                </div>

                @if($question->clarification != NULL && $_SESSION['educational'] == 1)
                <div class="clarification">
                    <b>Explanation:</b><br><br>
                    <?php echo $question->clarification; ?>
                </div>
                @endif
            @endif

        </div>

        <input type="submit" class="btn btn-block" value="{{ $btn_test }}" />

    </form>
@endsection
