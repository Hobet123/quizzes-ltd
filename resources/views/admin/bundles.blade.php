@extends('layouts.app_admin')

    @section('title', 'Quizzes')

    @section('content')
    <div class="pt-3">
            <h3>Bundles List:</h3>
        </div>
        <div class="w-100 text-end d-flex flex-row-reverse mb-3">
            <div class="me-9">
                <a href="/admin/uploadBundle"><button class="btn btn-outline-danger">Upload Bundle</button></a>
            </div>
        </div>
        @if(!empty($bundles))
            <div class="container">
            @foreach ($bundles as $bundle)  
            <div class="row m-1">
                <div class="col-10 text-start">
                    # {{ $bundle->id }}: {{ $bundle->quiz_name }}
                </div>    
                <div class="col-1">
                    <a href="/admin/editBundle/{{ $bundle->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                </div>
                <div class="col-1">
                    <a href="/аdmin/deleteBundle/{{ $bundle->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                </div>
            </div>   
            @endforeach
            </div>

        @endif

    @endsection
