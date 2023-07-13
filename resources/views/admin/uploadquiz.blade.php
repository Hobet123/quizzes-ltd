@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <header class="header">
        <h1>Upload Quiz:</h1>
    </header>
    <form action="/admin/doUploadQuiz" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />

        <div class="form-group form-control">
            <label for="quiz_name">Quiz Name</label>
            <input type="text" name="quiz_name" value="{{ old('quiz_name') }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="category">Category</label>
            <input type="text" name="category" value="{{ old('category') }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="meta_keywords">Meta Keyword (Put comma(,) separated)</label>
            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" maxlength="255" />
        </div>  
        <div class="form-group form-control">
            <label for="Featured">Featured</label>
            Yes &nbsp;&nbsp; <input type="checkbox" id="featured" name="featured" value="1">
        </div>
        <div class="form-group form-control">
            <label for="Active">Active</label>
            Yes &nbsp;&nbsp; <input type="checkbox" id="active" name="active" value="1">
        </div>
        <div class="form-group form-control">
            <label for="quiz_price">Quiz Price</label>
            <input type="text" name="quiz_price" value="{{ old('quiz_price') }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="short_description">Short Description</label>
            <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="255" />
        </div>
        <div class="form-group form-control">
            <label for="quiz_name">Quiz Description</label>
            <textarea name="quiz_description" value="">{{ old('quiz_description') }}</textarea>
        </div>
        <div class="form-group form-control">
            <label for="cover_image">Cover Image</label>
            <input type="file" name="cover_image" value="{{ old('cover_image') }}" />
        </div>
        <div class="form-group form-control">
            <label for="xlsx">XLSX File</label>
            <input type="file" name="xlsx" value="{{ old('xlsx') }}" />
        </div>
        <div class="form-group form-control">
            <label for="questions_images">Questions Images</label>
            <input type="file" name="questions_images" value="{{ old('questions_images') }}" />
        </div>
        <div class="form-group form-control">
            <label for="questions_images">Questions per Part</label>
            <input type="text" style="width: 45px;" name="per_part" value="20" />
        </div>
        <div class="form-group form-control mb-5">
              <input type="submit" class="btn btn-block" value="Upload" />
        </div>

    </form>
@endsection
