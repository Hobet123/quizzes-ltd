@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <div>
        <h1>Edit Quiz:</h1>
    </div>
    <div class="container">
    <form action="/admin/doEditQuiz" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}" />

        <div class="form-group form-control">
            <label for="quiz_name">Quiz Name</label>
            <input type="text" name="quiz_name" value="{{ $quiz->quiz_name }}" />
        </div>
        <div class="form-group form-control">
            <label for="category">Category</label>
            <input type="text" name="category" value="{{ $quiz->category }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="meta_keywords">Meta Keyword (Put comma(,) separated)</label>
            <input type="text" name="meta_keywords" value="{{ $quiz->meta_keywords }}" maxlength="255" />
        </div>    
        <div class="form-group form-control">
           Yes &nbsp;&nbsp; <label for="Featured">Featured</label>
            <input type="checkbox" id="featured" name="featured" value="1" @if ($quiz->featured === 1) checked=checked @endif>
        </div>
        <div class="form-group form-control">
            <label for="quiz_price">Quiz Price</label>
            <input type="text" name="quiz_price" value="{{ $quiz->quiz_price }}" />
        </div>
        <div class="form-group form-control">
            <label for="short_description">Short Description</label>
            <textarea name="short_description" value="">{{ $quiz->short_description }}</textarea>
        </div>
        <div class="form-group form-control">
            <label for="quiz_description">Quiz Description</label>
            <textarea name="quiz_description" value="">{{ $quiz->quiz_description }}</textarea>
        </div>
        <div class="form-group form-control">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" value="" /> 
            <p><img src="/cover_images/{{ $quiz->cover_image }}" width="100" alt=""></p>
        </div>
        <div class="form-group form-control">
            <label for="xlsx">XLSX File</label>
            <input type="file" name="xlsx" value="" />
        </div>
        <div class="form-group form-control">
            <label for="questions_images">Questions Images</label>
            <input type="file" name="questions_images" value="" />
        </div>
        <div class="form-group form-control">
            <label for="questions_images">Questions per Part</label>
            <input type="text" style="width: 45px;" name="per_part" value="{{ $quiz->per_part }}" />
        </div>
        <div>
            <h2>Questions:</h2>
            <div style="width: 100%; text-align: center; margin-bottom: 20px;"><a href="/admin/editQuizQAs/{{ $quiz->id }}">Edit Questions</a></div>
        </div>
        <input type="submit" class="btn btn-block" value="Edit Quiz" />

    </form>
    </div>
@endsection
