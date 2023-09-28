@extends('layouts.app_admin')

    @section('title', 'Pages')

    @section('content')
        <div class="pt-3">
            <h3>Users List:</h3>
        </div>
        <div class="w-100 text-end p-3">
            <a href="/admin/createPage"><button class="btn btn-outline-danger">Add Page</button></a>
        </div>
        @if(!empty($pages))
            <div class="container">
            @foreach ($pages as $page)  
                <div class="row border-bottom">
                    <div class="col-10 p-3">
                        {{ $page->title }} ({{ $page->page_name_url }})
                    </div>
                    <div class="col-2 p-3">
                        <a href="/admin/editPage/{{ $page->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    </div>
                    <!-- <div class="col-1 p-3">
                        <a href="/admin/deletePage/{{ $page->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    </div> -->
                </div>     
            @endforeach
            </div>

        @endif

    @endsection
