@extends('layouts.app_admin')

    @section('title', 'Pages')

    @section('content')
        <div class="pt-3">
            <h3>Items List:</h3>
            <p> - ordered by: {{ $user_email }}, order#: {{ $order_id }}</p>
        </div>
        <div class="w-100 text-end p-3">
        </div>
        @if(!empty($order_items))
            <div class="container">
                <div class="row border-bottom">
                    <div class="col-3 p-3">
                        <b>Item id</b>
                    </div>
                    <div class="col-3 p-3">
                        <b>Quiz id</b>
                    </div>
                    <div class="col-3 p-3">
                        <b>Quiz Name</b>
                    </div>
                    <div class="col-3 p-3">
                        <b>Quiz Price</b>
                    </div>
                </div>  
            @foreach ($order_items as $item)  
                <div class="row border-bottom">
                    <div class="col-3 p-3">
                        {{ $item->item_id }}
                    </div>
                    <div class="col-3 p-3">
                        {{ $item->quiz_id }}
                    </div>
                    <div class="col-3 p-3">
                        {{ $item->quiz_name }}
                    </div>
                    <div class="col-3 p-3">
                        ${{ $item->quiz_price }}
                    </div>

                </div>     
            @endforeach
            </div>

        @endif

    @endsection
