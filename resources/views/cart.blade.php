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
<?php
    if(isset($_SESSION['user']) && $_SESSION['user_id'] == 777){
        unset($_SESSION['user']);
        unset($_SESSION['user_id']);
    }

?>
        @if(empty($_SESSION['user']))

            

            Please <a href="/logIn"><button class="btn btn-success">Login</button></a> or 
            <a href="/signUp"><button class="btn btn-success">Signup</button></a> to process the purchase
            <?php
            $_SESSION['cart'] = 1;
            ?>
    
        @else
        {{ $_SESSION['user'] }}
        
        <a href="/checkout"><button class="btn btn-block">Checkout</button>
            <?php
            $_SESSION['cart'] = 0;
            ?>
        @endif
        </div>
        <script src="/js/cart.js"></script>
        
    @endsection
