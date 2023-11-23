<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>

    <link rel="/css/stylesheet" href="/css/checkout_stripe.css" />
    <script src="https://js.stripe.com/v3/"></script>

    <script src="/js/checkout_products.js" defer></script>
    <script src="/js/checkout_stripe.js" defer></script>
    <link rel="stylesheet" href="/css/checkput_stripe.css">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>

    <header>
        <!-- Your header content goes here -->
        Header
    </header>

    <div class="container">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
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
    <!-- Display a payment form -->
    <form id="payment-form">
      <div id="payment-element">
        <!--Stripe.js injects the Payment Element-->
      </div>
      <button id="submit">
        <div class="spinner hidden" id="spinner"></div>
        <span id="button-text">Pay now</span>
      </button>
      <div id="payment-message" class="hidden"></div>
    </form>

    <footer>
        <!-- Your footer content goes here -->
        Footer
    </footer>

</body>
</html>
