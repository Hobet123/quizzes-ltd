<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        // Replace with your actual Stripe secret key
        $stripeSecretKey = config('services.stripe.secret');
        $stripe = new StripeClient($stripeSecretKey);

        try {
            // Retrieve JSON from the request
            $items = $request->json('items');

            // Create a PaymentIntent with amount and currency
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $this->calculateOrderAmount($items),
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            // Return the client secret in the response
            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            // Handle errors and return a 500 Internal Server Error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Function to calculate the order amount based on items
    private function calculateOrderAmount(array $items): int
    {
        // Replace this constant with a calculation of the order's amount
        // Calculate the order total on the server to prevent
        // people from directly manipulating the amount on the client
        return 1400;
    }
}
