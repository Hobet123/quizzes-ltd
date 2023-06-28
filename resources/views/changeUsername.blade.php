@extends('layouts.app')

@section('title', 'User Form Login')

@section('content')
    <h4 class="title_h2">
        Change Your Name:
    </h4>
    <form action="/doChangeUsername" method="post" enctype="multipart/form-data" class="">
        @csrf
        <div class="form-group form-control">
            <label for="new_username">New Name</label>
            <input type="text" name="new_username" value="" />
        </div>        

        <input type="submit" class="btn btn-block" value="Change Username" id="login" />


    </form>
@endsection
