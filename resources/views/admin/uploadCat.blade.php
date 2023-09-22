@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <header class="header">
        <h1>Add Category:</h1>
    </header>
    <form action="/admin/doUploadCat" method="post" enctype="multipart/form-data" class="">
        @csrf
        <div class="form-group form-control">
            <label for="quiz_name">Parent Category: (if applicable)</label>
            <select name="parent_id" id="">
                <option value="0">Select...</option>
                @foreach($cats as $cat)
                    <option value="{{ $cat->id }}">
                        {{ $cat->cat_name }}
                    </option>
                @endforeach
            </select>
            
        </div>
        <div class="form-group form-control">
            <label for="category">Category Name</label>
            <input type="text" name="cat_name" value="{{ old('cat_name') }}" maxlength="255" />
        </div>
        <input type="submit" class="btn btn-block" value="Add" />

    </form>
@endsection
