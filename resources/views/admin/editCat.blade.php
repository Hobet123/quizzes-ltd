@extends('layouts.app_admin')

@section('title', 'Upload Quiz')

@section('content')
    <header class="header">
        <h1>Add Category:</h1>
    </header>
    <form action="/admin/doEditCat" method="post" enctype="multipart/form-data" class="">
        @csrf
        <input type="hidden" name="cat_id" value="{{ $cat->id}}" />
        <div class="form-group form-control">
            <label for="quiz_name">Parent Category: (if applicable)</label>
            <select name="parent_id" id="">
                <option value="0">Select...</option>
                @foreach($all_cats as $now_cat)
                    <option value="{{ $now_cat->id }}"{{ $cat->parent_id == $now_cat->id ? " selected" : "" }}>
                        {{ $now_cat->cat_name }}
                    </option>
                @endforeach
            </select>
            
        </div>
        <div class="form-group form-control">
            <label for="category">Category Name</label>
            <input type="text" name="cat_name" value="{{ isset($cat) ? $cat->cat_name : old('cat_name') }}" maxlength="255" />
        </div>
        <input type="submit" class="btn btn-block" value="Edit" />

    </form>
@endsection
