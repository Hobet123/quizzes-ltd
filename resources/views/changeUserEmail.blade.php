@extends('layouts.app')

@section('title', 'User Form Login')

@section('content')
    <h4 class="title_h2">
        Change Email:
    </h4>
    </header>
    <form action="/doChangeUserEmail" method="post" enctype="multipart/form-data" class="">
        @csrf

        <div class="form-group form-control">
            <label for="email">New Email:</label>
            <div>
                <input type="email" id="email" name="email" required />
             </div>
        </div>

        <input type="submit" class="btn btn-block" value="Change Email" id="change_email" />

    </form>
@endsection
