@extends('layouts.app')

@section('title', 'Add Session')

@section('content')
    <header class="header">
        <h1>Question {{ $data['qn_count'] }} of 5:</h1>
    </header>
    <form action="/answer/{{ $data['question']->id }}" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="qn_count" value="{{ $data['qn_count'] }}">
        <input type="hidden" name="qn_id" value="{{ $data['question']->id }}">
        <div class="form-group form-control">
            {{ $data['question']->q_name }}
        </div>
        @if ($data['question']->q_image != null)
            <div>
                <img src="/questions_images/q_{{ $_SESSION['quiz_id'] }}/sample_images/{{ $data['question']->q_image }}"
                    height="100" />
            </div>
        @endif
        <div class="form-group">
            <div>Select Answer:</div>
            @foreach ($data['answers'] as $answer)
                <div class="radio_choice"><input type="radio" name="answer_id"
                        value="{{ $answer->id }}">&nbsp;&nbsp;&nbsp;{{ $answer->a_name }}
                </div>
            @endforeach
        </div>

        <input type="submit" class="btn btn-block" value="Submit Answer" />

    </form>
@endsection
