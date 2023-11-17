<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use App\Home;
use App\Quize;
use App\Find;

use App\Categorie;

use App\BlueMail;

use Mail;

use App\Mail\FeedbackMail;
use App\Mail\GeneralMail;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\HelperController;

use App\Http\Controllers\JsonController;

use App\Http\Controllers\CategorieController;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class StripeController extends Controller
{

    public function checks()
    {
        dd("here str");

        return view('checkoutStripe');
    }



    public function sessionStripe()
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $session = \Stripe\Checkout\Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'gbp',
                        'product_data' => [
                            'name' => 'gimme money!!!!',
                        ],
                        'unit_amount'  => 500,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            // 'success_url' => route('successStripe'),
            'success_url' => 'https://nquiz.philipp.ink/successStripe',
            'cancel_url'  => route('checks'),
        ]);

        return redirect()->away($session->url);
    }

    public function successStripe()
    {
        return "Yay, It works!!!";
    }
}