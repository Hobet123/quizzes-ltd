<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Accept a payment</title>
    <meta name="description" content="A demo of a payment on Stripe" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="/css/stylesheet" href="/css/checkout_stripe.css" />
    <script src="https://js.stripe.com/v3/"></script>

    <!-- <script src="/js/cart_main.js" defer></script> -->
    <script src="/js/checkout_products.js" defer></script>
    <script src="/js/checkout_stripe.js" defer></script>

    <link rel="stylesheet" href="/css/checkout_stripe.css">
    <link rel="stylesheet" href="/css/checkout_quiz.css">
    
    <!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
    
  </head>
  <body data-csrf-token="{{ csrf_token() }}" data-user-id="{{ $user_id }}" data-user-email="{{ $user_email }}" data-app-url="{{ $app_url }}" data-stripe-pk="{{ $stripe_pk }}">
    <header>
      <a href="/" id="brand">Quizzes.ltd</a>
    </header>

      <div class="container" id="stripe">
        <p>
          <b>Summary:</b>
        <hr>
        <div id="cart-items">
            <!-- The cart items will be dynamically generated here -->
        </div>
        <hr>
        <p><b>Total: <span id="total-price" style="color: red;">$0.00</span><b></p>
        
        </p>
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
        <!-- End payment form -->
      </div>
    <footer>
        <!-- Your header content goes here -->
    </footer>

  </body>
</html>

