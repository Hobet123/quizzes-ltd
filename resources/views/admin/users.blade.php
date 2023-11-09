@extends('layouts.app_admin')

    @section('title', 'Users')

    @section('content')
        <div class="pt-3 d-flex justify-content-between">
            <div class="ps-4">
                <h4>Users List:</h4>
            </div>
            <div class="">
            @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)
                <a href="/admin/createUser"><button class="btn btn-outline-danger">Add User</button></a>
            @endif
            </div>
        </div>
        
        @if(!empty($users))
            <div class="container">
            @foreach ($users as $user)  
                <div class="row m-1 p-3 border-bottom">
                    <div class="col-10">
                        {{ $user->username }}  - {{ $user->email }}
                    </div>
                    <div class="col-1">
                    @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)
                        <a href="/admin/editUser/{{ $user->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                    @endif
                    </div>
                    <div class="col-1">
                    @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)
                        <a href="/admin/deleteUser/{{ $user->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                    @endif
                    </div>
                </div>     
            @endforeach
            </div>
            <div class="pt-3 ps-3">
                <h4>Admins List:</h4>
            </div>
            <div class="container">
            @foreach ($admins as $admin)
                <div class="row m-1 p-3 border-bottom">
                    <div class="col-10">
                        @if($admin->is_admin == 2)
                            <i class="fa-sharp fa-solid fa-user-secret"></i>&nbsp; &nbsp;{{ $admin->username }} - {{ $admin->email }} (superadmin)
                        @else
                            <i class="fa-sharp fa-solid fa-gears"></i>&nbsp;&nbsp;{{ $admin->username }} - {{ $admin->email }}
                        @endif
                    </div>
                    <div class="col-1">
                        @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)
                            <a href="/admin/editUser/{{ $admin->id }}"><i class="fa-solid fa-pen-to-square fa-lg"></i></a>
                        @endif
                    </div>
                    <div class="col-1">
                        @if(isset($_SESSION['super_admin']) && $_SESSION['super_admin']== 2)
                            <a href="/admin/deleteUser/{{ $admin->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                        @endif
                    </div>
                </div>     
            @endforeach
            </div>

        @endif

    @endsection
