@extends('layouts.app_admin')

    @section('title', 'Quizzes')

    @section('content')
    <div class="pt-3">
            <h3>Categories List:</h3>
        </div>
        <div class="w-100 text-end d-flex flex-row-reverse mb-3">
            <div class="me-3">
            @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)
                <a href="/admin/uploadCat"><button class="btn btn-outline-danger">Add Category</button></a>
            @endif
            </div>
        </div>
        @if(!empty($cats))
            <div class="container">
            @foreach ($cats as $cat)  
            <div class="row m-1 p-3 border-bottom">
                <div class="col-10 text-start">
                    @if($cat->parent_cat_name)

                        {{ $cat->parent_cat_name }} > 

                    @endif
                    
                    {{ $cat->cat_name }}
                </div> 
                @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)  
                <div class="col-1">
                    <a href="/admin/editCat/{{ $cat->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                </div>
                <div class="col-1">
                    <a href="/delete_cat/{{ $cat->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                </div>
                @endif
            </div>   
            @endforeach
            </div>

        @endif

    @endsection
