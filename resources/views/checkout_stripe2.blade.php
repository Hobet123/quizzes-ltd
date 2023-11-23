<?php
    $userId = $_SESSION['user_id'];
?>
@extends('layouts.app_stripe')

    @section('title', 'Summary - Checkout')

    @section('content')

      <link rel="/css/stylesheet" href="/css/checkout_stripe.css" />
      <script src="https://js.stripe.com/v3/"></script>
      <script src="/js/checkout_stripe.js" defer></script>

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

    @endsection