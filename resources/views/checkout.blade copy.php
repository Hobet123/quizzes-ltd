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

    <div class="contaner d-flex justify-center" style="width: 100%;">
        <!-- <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="paypal-form"> -->
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal-form">
            <input type="hidden" name="cmd" value="_xclick">
            <!-- <input type="hidden" name="business" value="pavel-merchant@gmail.com"> -->
            <input type="hidden" name="business" value="pay@evector.biz">
            <input type="hidden" name="currency_code" value="CAD">
            <input type="hidden" name="no_shipping" value="1">
            <!-- <input type="hidden" name="user_id" value="23"> -->

            <!-- <input type="hidden" name="return" value="<URL to redirect to after payment completion>"> -->
            <input type="hidden" name="return" value="{{ env('APP_URL') }}/pp_completed">

            <!-- <input type="hidden" name="cancel_return" value="<URL to redirect to if payment is canceled>"> -->
                <input type="hidden" name="cancel_return" value="{{ env('APP_URL') }}/pp_canceled">
            
            <!-- <input type="hidden" name="notify_url" value="<URL to receive IPN notifications>"> -->
            <input type="hidden" name="notify_url" value="{{ env('APP_URL') }}/pp_notify">
        
            <input type="hidden" id="item_name" name="item_name" value="Online Quiz Purchase">
            <input type="hidden" id="amount" name="amount" value="10.00">



            

        </form>
        <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
                <img src="images/paypal.jpeg" style="width: 400px; cursor: pointer;" alt="Submit" onclick="document.getElementById('paypal-form').submit()">
            </div>
    </div>
    <script src="/js/checkout.js"></script>
        <!-- 
/////////////////////////////////////
            4214027771329288
            0124
            250
/////////////////////////////////////

        Card Type: Visa
        Card Number: 4214027771329288
        Expiration Date: 01/2024
        CVV: 250
        

        Make sure to replace <Your Sandbox Business Email Address>, <URL to redirect to after payment completion>, 
            <URL to redirect to if payment is canceled>, and <URL to receive IPN notifications> with your own values.

        This form will allow you to accept payments for a test item with a fixed price of $10 USD. When the form is submitted, it will redirect the user to the PayPal payment page where they can pay with their PayPal account or credit/debit card.

        Note that this form is for testing purposes only and should not be used in a production environment without further development and testing. 


        -->
    @endsection
