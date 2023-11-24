@extends('layouts.app_admin')

    @section('title', 'Pages')

    @section('content')
        <div class="pt-3">
            <h3>Orders List:</h3>
        </div>
        <div class="w-100 text-end p-3">
        </div>
        @if(!empty($orders))
            <div class="container">
                <div class="row border-bottom">
                    <div class="col-3 p-3">
                        <b>User Email</b>
                    </div>
                    <div class="col-2 p-3">
                    <b>Order Status</b>
                    </div>
                    <div class="col-1 p-3">
                    <b>Amount</b>
                    </div>
                    <div class="col-2 p-3">
                    <b>Details</b>
                    </div>
                    <div class="col-2 p-3">
                    <b>Updated At</b>
                    </div>
                    <div class="col-1 p-3">
                        <!-- <a href=""><i class="fa-solid fa-trash fa-lg"></i></a> -->
                    </div>
                </div>  
            @foreach ($orders as $order)  
                <div class="row border-bottom">
                    <div class="col-3 p-3">
                        {{ $order->user_email }}
                    </div>
                    <div class="col-2 p-3">
                        {{ $order->order_status }}
                    </div>
                    <div class="col-1 p-3">
                        ${{ $order->amount }}
                    </div>
                    <div class="col-2 p-3">
                        <a href="/admin/orderDetails/{{ $order->id }}">More...</a>
                        <!-- <a href="/admin/orderDetails/{{ $order->id }}">More...</a> -->
                    </div>
                    <div class="col-2 p-3">
                        {{ $order->updated_at }}
                    </div>
                    <div class="col-1 p-3">
                        <!-- <a href=""><i class="fa-solid fa-trash fa-lg"></i></a> -->
                        <!-- /admin/deleteOrder/{{ $order->id }} -->
                    </div>
                </div>     
            @endforeach
            </div>

        @endif

    @endsection
