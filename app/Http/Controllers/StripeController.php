<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

use App\Order;

use App\Admin;
use App\User;
use App\Home;

use App\Session;
use App\Find;

use App\BlueMail;

use App\Xlsx;
use App\Quize;
use App\Question;
use App\Answer;

use ZipArchive;

use App\Http\Controllers\XlsxController;

use App\Http\Controllers\ManageUserControler;
use App\Http\Controllers\JsonControler;

use App\Http\Controllers\CategorieControler;

use Illuminate\Support\Facades\DB;

use Illuminate\Filesystem\Filesystem;
/*
*/

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

use Stripe\Stripe;

use Stripe\StripeClient;

class StripeController extends Controller
{
    public function __construct(){
        session_start();
    }

    public function createPaymentIntent(Request $request)
    {

        $items = $request->json('items');

        // return response()->json($this->ca1lculateOrderAmount($items));

        // $items = {0:120};
        
        ////////////////////////////////////

        $stripeSecretKey = env('STRIPE_SK');
        
        $stripe = new StripeClient($stripeSecretKey);

        try {

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $this->ca1lculateOrderAmount($items),
                // 'amount' => 1400,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            //

                // $order = self::createOrder($items, $paymentIntent->client_secret);

            // pi_3OFbglIa7Ttd6va40MpnJ0Ux_secret_Kk53eVFm196MeyCCB0AtIijRR -pi_3OFbglIa7Ttd6va40MpnJ0Ux

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        // dd();
    }

    private static function ca1lculateOrderAmount($items)
    {
        $amount = 0;

        foreach($items as $item){

            $quiz = Quize::find($item);

            $amount += $quiz->quiz_price;
        }
        
        // return 1400;

        return $amount*100;
    }

    public static function checkoutStripe(){

        return view("checkout_stripe");

    }
}
