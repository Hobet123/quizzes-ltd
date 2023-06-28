@extends('layouts.app')

    @section('title', 'Summary - Checkout')

    @section('content')

    <div class="container">
            <h3><b>Summary:</b></h3>
            <hr>
            <div id="cart-items">
                <!-- The cart items will be dynamically generated here -->
            </div>
            <hr>
            
            <div id="">
                <p><b>Total: <span id="total-price" style="color: red;">$0.00</span><b></p>
            </div>

        </div>  

    <!-- <div class="" style="margin:0 auto; width:50%;"> -->
    <div class="">
        <iframe src="/paypal.php" height="1050" width="100%" title="Iframe Example"></iframe>
    </div>
    <script src="/js/checkout.js"></script>
        <!-- 
/////////////////////////////////////
            4214027771329288
            0124
            250
/////////////////////////////////////
-->
    @endsection
