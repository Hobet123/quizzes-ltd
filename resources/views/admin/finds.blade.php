@extends('layouts.app_admin')

    @section('title', 'Searches')

    @section('content')
        <div class="text-start mt-3">
            <h4>Searches List:</h4>
        </div>  
        @if(!empty($finds))
            <div class="container">
                @foreach ($finds as $find)
                    <div class="row m-1 p-3 border-bottom" style="border-bottom: 1px;">
                        <div class="col-6 text-start">
                            {{ $find->keys }}
                        </div>
                        <div class="col-5">
                            {{ $find->created_at }}
                        </div>
                        <div class="col-1">
                            <a href="/admin/deleteFind/{{ $find->id }}"><i class="fa-solid fa-trash fa-lg"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @endsection
