@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <header class="header">
        <h1>Edit Home:</h1>
    </header>
    <form action="/admin/doHomeSetting" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="home_id" value="{{ $home->id }}" />
        <div class="form-group form-control">
            <label for="quiz_name">Title</label>
            <input type="text" name="title" value="{{ $home->title }}" />
        </div>
        <div class="form-group form-control">
            <label for="main_text">Main Text</label>
            <textarea name="main_text">{{ $home->main_text }}</textarea>
        </div>
        <!-- <div class="form-group form-control">
            <label for="">Copyright</label>
            <input type="text" name="" value="{{ $home->copyright }}" /> 
        </div> -->

        <input type="submit" class="btn btn-block" value="Update" />

    </form>
@endsection
