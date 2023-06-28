@extends('layouts.app')

    @section('title', 'Cart')

    @section('content')

        <div class="container">
            <h3><b>Cart:</b></h3>
            <hr>
            <div id="cart-items">
                <!-- The cart items will be dynamically generated here -->
            </div>
            <hr>
            
            <div id="">
                <p><b>Total: <span id="total-price" style="color: red;">$0.00</span><b></p>
            </div>

        </div>
        <div class="container">
        @if(empty($_SESSION['user']))

            Please <a href="/logIn"><button class="btn btn-success">Login</button></a> or 
            <a href="/signUp"><button class="btn btn-success">Signup</button></a> to process the purchase
            <?php
            $_SESSION['cart'] = 1;
            ?>
    
        @else 
        <a href="/checkout"><button class="btn btn-block">Checkout</button>
            <?php
            $_SESSION['cart'] = 0;
            ?>
        @endif
        </div>
        <script src="/js/cart.js"></script>
        
    @endsection
