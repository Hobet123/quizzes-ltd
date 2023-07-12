@extends('layouts.app_admin')

@section('title', 'Edot Page')

@section('content')
    <header class="header">
        <h1>Create Page:</h1>
    </header>
    <form action="/admin/doCreatePage" method="post" enctype="multipart/form-data" class="">
        @csrf

        <div class="form-group form-control">
            <label for="page_name_url">Page Name URL</label>
            <input type="text" name="page_name_url" value="" />
        </div>
        <div class="form-group form-control">
            <label for="quiz_name">Title</label>
            <input type="text" name="title" value="" />
        </div>
        <div class="form-group form-control">
            <label for="quiz_name">Meta Keywords</label>
            <input type="text" name="meta_keywords" value="" />
        </div>
        <div class="form-group form-control">
            <label for="main_text">Meta Description</label>
            <textarea name="meta_description"></textarea>
        </div>
        <div class="form-group form-control">
            <label for="main_text">Main Text</label>
            <textarea name="main_text"></textarea>
        </div>

        <input type="submit" class="btn btn-block" value="Create Page!" />

    </form>
@endsection
