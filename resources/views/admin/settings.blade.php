@extends('layouts.app_admin')

    @section('title', 'Users')

    @section('content')
        <div class="pt-3">
            <h3>Static Pages:</h3>
        </div>
        <div class="w-100 text-end">
            <a href="/admin/createPage"><button class="btn btn-outline-danger">Add User</button></a>
        </div>
        @if(!empty($pages))
            <div class="container">
            @foreach ($pages as $page)  
                <div class="row">
                    <div class="col-10">
                        {{ $page->title }}
                    </div>
                    <div class="col-1">
                        <a href="/admin/editPage/{{ $page->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    </div>
                    <div class="col-1">
                        <a href="/admin/deletePage/{{ $user->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    </div>
                </div>     
            @endforeach
            </div>

        @endif

    @endsection
