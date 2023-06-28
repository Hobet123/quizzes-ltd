@extends('layouts.app_admin')

@section('title', 'Add Question')

@section('content')
    <header class="header">
        <h2>Add Question and Answers:</h2>
        <?php 
        //echo $_SESSION['quiz_id']; 
        ?>
    </header>
    <form action="/admin/doAddDuQA" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        <!-- 1mb -->

        <div class="form-group form-control">
            <div>Question:</div>
            <input type="text" name="question" value="">
        </div>
        <div class="form-group form-control">
            <div>Question:</div>
            <input type="file" name="q_image" value="{{ old('question') }}">
        </div>
        <div class="form-group form-control">
            <div class="form-group">
                <div>Answers:</div>
                <div id="answers">
                    <div><i class="fa-sharp fa-solid fa-square-plus fa-lg" id="add-answer"></i></div>
                    <div class="answer">
                        <div id="answer_1" class="flex-container answer-block">
                            <div style="padding: 0px 10px;"><input type="radio" name="correct_a" value="1" checked></div>
                            <div><input type="text" name="answer_1" style="width: 90%;" value="{{ old('answer_1') }}"></div>
                        </div>
                        <div id="answer_2" class="flex-container answer-block">
                            <div style="padding: 0px 10px;"><input type="radio" name="correct_a" value="1"></div>
                            <div><input type="text" name="answer_2" style="width: 90%;" value="{{ old('answer_2') }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-button">
            <div><input type="submit" name="submit" class="btn btn-block" value="Add Question" /></div>
            @if(!isset($_SESSION['editing_quiz']))
                <div><input type="submit" name="submit" class="btn btn-block" value="Finish" /></div>
            @endif
        </div>

    </form>
    <script>
        $(document).ready(function(){
            
            var i = 2;
            
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
