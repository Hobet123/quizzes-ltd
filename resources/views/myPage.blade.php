@extends('layouts.app')

    @section('title', 'My page')

    @section('content')
        <div class="container d-flex mt-3">
            <div class="me-5"><b></datagrid><a href="/changePassword">Change Password</a></b></div>
            <div><b></datagrid><a href="/changeUsername">Change Your Name</a></b></div>
            <div><b></datagrid><a href="/changePhone">Change Your Phone</a></b></div>
            
        </div>
        <div><hr></div>
        
        <div class="row">
            @foreach($sessions as $session)
            <div class="col-md-4 mb-4">
                <div class="card m-1" style="">

                    <div class="card-image" style="background-image: url('{{ env('APP_URL') }}/cover_images/{{ $session->cover_image }}');"></div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $session->quiz_name }}</h5>
                        <a href="/quizHome/{{ $session->quiz_id }}" class="btn btn-danger">Start Quiz</a>
                    </div>

                </div>
            </div>
            @endforeach

        </div>
         
    @endsection
