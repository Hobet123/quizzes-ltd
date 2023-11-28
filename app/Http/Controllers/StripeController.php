<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;

use App\Order;
use App\Orderitem;

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

        $items = isset($_GET['items']) ? $_GET['items'] : null;

        // $items = json_decode($items, true);

        $items = explode(',', $items);

        // dd($items);

        // $items = $request->json('items');

        // $items = ['84', '120', '122'];

        $user_id = $items[0];

        unset($items[0]);

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

            $order = self::createOrder($user_id, $items, $paymentIntent->client_secret);

            //pi_3OFkhpIa7Ttd6va42RUontNq_secret_lCpDm6n8suBDFoujTNY94Jg3o
            //pi_3OFkhpIa7Ttd6va42RUontNq

            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public static function createOrder($user_id, $items, $paymentIntent){

        $user = User::find($user_id);
        $user_email = $user->email;

        $parts = explode("_", $paymentIntent);
        $stripe_id = $parts[1];

        $new_order = new Order;

        $new_order->stripe_id = $stripe_id;
        $new_order->user_id = $user_id;
        $new_order->user_email = $user_email;
        $new_order->order_status = "Order Created";
        $new_order->save();

        $order_id = $new_order->id;

        foreach($items as $item){

            $new_item = new Orderitem;

            $new_item->order_id = $order_id;
            $new_item->quiz_id = $item;

            $new_item->save();

        }

        return true;
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

        if(empty($_SESSION['user_id'])){
            return redirect('/logIn');
        }
        
        $user_id = $_SESSION['user_id'];

        echo $user_id;
        
        // dd($user_id);

        $user = User::find($user_id);

        $user_email = $user->email;

        // dd($user_email);

        return view("checkout_stripe", ['user_id' => $_SESSION['user_id'], 'user_email' => $user_email, 'app_url' => env('APP_URL')]);

    }

    public static function confirmOrder(){
        
        // dd($_GET['order_id']);//pi_3OFkhpIa7Ttd6va42RUontNq

        $parts = explode("_", $_GET['order_id']);

        $stripe_id = $parts[1];

        $order = Order::where('stripe_id', $stripe_id)->first();

        if($order != null){
            
            $order->order_status = "Paid - ".date('H:i:s');
            $order->save();

            $user_id = $order->user_id;
            $order_id = $order->id;

            // dd($user_id." - ".$order_id);

            $result = self::addSessions($user_id, $order_id);

            return true;
        }
        else{
            return false;
        }

    }

    public static function addSessions($user_id, $order_id){

        $orderItems = Orderitem::where('order_id', $order_id)->get();

        foreach($orderItems as $cur_item){

            $quiz_id = $cur_item->quiz_id;

            $new_session = new Session;

            $new_session->quiz_id = $quiz_id;
            $new_session->user_id = $user_id;

            $new_session->save();

        } 

        return true;

    }

    public static function orderAmount($order_id){

        $amount = 0;

        $order_items = DB::select(
        'SELECT q.quiz_price, oi.id, oi.quiz_id
        FROM orderitems oi 
        INNER JOIN quizes q 
        ON oi.quiz_id = q.id
        WHERE oi.order_id = :order_id
        ', ['order_id' => $order_id]);

        foreach($order_items as $cur){
            $amount += $cur->quiz_price;
        }

        // dd($amount);

        return $amount;

    }
    //
    public static function orderDetails($order_id){

        $order_items = DB::select(
        'SELECT q.quiz_price, oi.id as item_id, oi.quiz_id, q.quiz_name, o.id as order_id, o.user_email
        FROM orderitems oi 
        INNER JOIN quizes q 
        ON oi.quiz_id = q.id
        INNER JOIN orders o 
        ON oi.order_id = o.id
        WHERE oi.order_id = :order_id
        ', ['order_id' => $order_id]);

        // dd($order_items);

        $user_email = $order_items[0]->user_email;

        return view('admin.orderDetails')->with(['order_items' => $order_items, 'order_id' => $order_id, 'user_email' => $user_email]);

    }


}
