<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlueMail extends Model
{

    public function __construct(){

    }

    public static function contactUs($message)
    {

        $htmlBody = "<html><hea></hea><body><p>$message</p></body></html>";

        $responce = self::sendBlueEmail("support@evector.biz", 'Contact Us - '.env('WEBSITE_NAME'), $htmlBody);    

        if($responce == 1){
            return "Email has been sent";
        }
        else {
            return "Error. Please contact admin!";
        }

    }

    public static function resetPasswordEmail($user_email, $email_hash2)
    {
        $reset_link = env('APP_URL').'/resetPasswordLink/'.$email_hash2;

        $htmlBody = "
            <h2>Hello!</h2>
            <b>Click the link below to reset your password</b><br><br>
            Link: $reset_link <br>
            Best Regards,<br>
            Team ".env('WEBSITE_NAME');

        $responce = self::sendBlueEmail($user_email, 'Reset Password - '.env('WEBSITE_NAME'), $htmlBody);

        if($responce == 1)
        {
            $msg = "Please check your email to reset password!";
        }
        else{
            $msg = "Something went wrong please contact support!";
        }

        return $responce;

    }

    public static function confirmEmail($user_email, $email_hash)
    {

        $email_link = env('APP_URL');
        $email_link .= '/confirmEmail/'.$email_hash;

        $htmlBody = "
            <h2>Hello!</h2>
            <b>Click the link below to confirm your email</b><br><br>
            Link: $email_link <br>
            Best Regards,<br>
            Team ".env('WEBSITE_NAME');

        // dd($user_email." -- ".$htmlBody);

        $responce = self::sendBlueEmail($user_email, 'Confirm Email - '.env('WEBSITE_NAME'), $htmlBody);

        if($responce == 1)
        {
            $msg = "Your email has been confirmed!";
        }
        else{
            $msg = "Something went wrong please contact support!";
        }

        return $responce;

    }

    public static function sendUserEmail($user_email, $user_password)
    {

        // $email_link = env('APP_URL');

        // $htmlBody = "
        //     <h2>Hello!</h2>
        //     <b>Click the link below to confirm your email</b><br><br>
        //     Link: $email_link <br>
        //     Best Regards,<br>
        //     Team ".env('WEBSITE_NAME');

        $htmlBody='<div style="width: 100%; color: white; background-color: #1a314e; padding: 10px; text-align: left;">
<b>Quizzes.Ltd</b>
</div>
<div style="padding: 30px; width:100%; background-color: lightblue;">
<h2>Welcome to '.env('WEBSITE_NAME').'!</h2>
<b>Enter '.env('APP_URL').'</b><br><br>
Username: '.$user_email.'<br>
Password: '.$user_password.'<br><br>
Best Regards,<br>
Team '.env('WEBSITE_NAME').'.
</div>
<div style="width: 100%; color: white; background-color: #1a314e; padding: 10px; text-align: center;">
(c) 2023
</div>';

        $responce = self::sendBlueEmail($user_email, 'Your login info - '.env('WEBSITE_NAME'), $htmlBody);

        if($responce == 1)
        {
            $msg = "Your email has been confirmed!";
        }
        else{
            $msg = "Something went wrong please contact support!";
        }

        return $responce;

    }

    public static function sendBlueEmail($to,  $subject = 'Contact Us', $htmlBody)
    {

        $htmlHeader='<div style="width: 100%; color: white; background-color: #1a314e; padding: 10px; text-align: left;">
        <b>Quizzes.Ltd</b>
        </div>
        <div style="padding: 30px; width:100%; background-color: lightblue;">
        <h2>Welcome to '.env('WEBSITE_NAME').'!</h2>';

        $data = array(
            "sender" => array(
                "email" => env('WEBSITE_EMAIL'),
                "name" => env('WEBSITE_NAME'),         
            ),
            "to" => array(
                array(
                    "email" => $to,
                    "name" => $to 
                )
            ),
            "subject" => $subject,
            "htmlContent" => $htmlBody
    
        );
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, 'https://api.sendinblue.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Api-Key: '.env('BLUE_API_KEY');
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $result = curl_exec($ch);
    
        if (curl_errno($ch)) {
            $responce = 'Error:' . curl_error($ch);
        }
        else{
            $responce = 1;

        }
        curl_close($ch);

        return $responce;
    }



}