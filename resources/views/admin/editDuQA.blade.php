@extends('layouts.' . $_SESSION['layout'])

@section('title', 'Add Session')

@section('content')
    <header class="header">
        <h2>Edit Question and Answers:</h2>
    </header>
    <form action="/admin/doEditDuQA" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        <input type="hidden" name="question_id" value="{{ $question->id }}">

        <div class="form-group form-control">
            <div>Question:</div>
            <input type="text" name="question" value="{{ old('question', $question->q_name) }}"
        </div>
        
        <div class="form-group form-control">
            <div>Question Image:</div>
            <input type="file" name="q_image" value="">
            <div class="image">
                @if($question->q_image != NULL)
                <img src="/questions_images/q_{{ $_SESSION['quiz_id'] }}/sample_images/{{ $question->q_image }}" width="100">
                @endif
            </div>
        </div>
        <div class="form-group form-control">
            <div class="form-group">
                <div>Answers:</div>
                <div id="answers">
                    <div><i class="fa-sharp fa-solid fa-square-plus fa-lg" id="add-answer"></i></div>
                    <div class="answer">
                        @php
                            $i = 1;
                        @endphp

                        @foreach($answers as $answer)
                            <div id="answer_{{ $i }}" class="flex-container answer-block">
                                <div style="padding: 0px 10px;"><input type="radio" name="correct_a" value="{{ $i }}"  
                                @if($answer->a_correct == 1)
                                checked
                                @endif
                                ></div>
                                @php
                                    $c_value = "answer_".$i;
                                @endphp
                                <div><input type="text" name="answer_{{ $i }}" style="width: 90%;" value="{{ old($c_value, $answer->a_name) }}"></div>
                                @if($i > 2)
                                <div style="padding: 10px;"><i class="fa-sharp fa-solid fa-delete-left fa-lg delete_answer" id="delete_{{ $i }}"></i></div>
                                @endif
                                @php
                                    $i++;
                                @endphp
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="form-group form-control">
                    <div>Explanation:</div>
                    <textarea name="clarification">{{ old('clarification', $question->clarification) }}</textarea>
                </div>
            </div>
        </div>
        <div class="flex-button">
            <div><input type="submit" name="submit" class="btn btn-block" value="Edit Question" /></div>
        </div>

    </form>
    <script>
        $(document).ready(function(){
            
            var i = <?php echo $i;?>;
            
            $("#add-answer").click(function(){

                i++; 

                console.log(i);

                new_a = '<div id="answer_'+i+'" class="flex-container answer-block">';
                new_a += '<div style="padding: 0px 10px;"><input type="radio" name="correct_a" value="'+i+'"></div>';
                new_a += '<div><input type="text" name="answer_'+i+'" style="width: 90%;" value=""></div>';
                new_a += '<div style="padding: 10px;"><i class="fa-sharp fa-solid fa-delete-left fa-lg delete_answer" id="delete_'+i+'"></i></div>';
                new_a += '</div>';

                $(".answer").append(new_a);

                $(".delete_answer").click(function(){

                    var id = $(this).attr('id');

                    id = id.replace("delete_", "");

                    $("#answer_" + id).remove();

                    console.log(id);

                });
                
            });

        });
    </script>

@endsection
