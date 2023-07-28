@extends('layouts.app_admin')

    @section('title', 'Users')

    @section('content')
        <div class="pt-3">
            <h3>Users List:</h3>
        </div>
        <div class="w-100 text-end d-flex flex-row-reverse mb-3s">
            <a href="/admin/createUser"><button class="btn">Add Users</button></a>
        </div>
        @if(!empty($users))
            <div class="container">
            @foreach ($users as $user)  
                <div class="row">
                    <div class="col-10">
                        {{ $user->username }}
                    </div>
                    <div class="col-1">
                        <a href="/admin/editUser/{{ $user->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    </div>
                    <div class="col-1">
                        <a href="/admin/deleteUser/{{ $user->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    </div>
                </div>     
            @endforeach
            </div>

        @endif

    @endsection
