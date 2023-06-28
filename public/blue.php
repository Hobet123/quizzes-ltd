<?php 

    // $temp = 9899898;

    // $temp2 = "asdasdssP{$temp}asdadssasd";

    // echo $temp2;

    // exit;

    $data = array(
        "sender" => array(
            "email" => 'paul.ph227@gmail.com',
            "name" => 'sender email'         
        ),
        "to" => array(
            array(
                "email" => "paul.ph227@gmail.com",
                "name" => "Your - name" 
            )
        ),
        "subject" => 'New testing for the - Random #: '.rand(),
        "htmlContent" => '<html><head></head><body><p>Hello,</p>This is my first transactional email sent from Sendinblue.</p></body></html>'

    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.sendinblue.com/v3/smtp/email');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Api-Key: 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';
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);


    print_r($result);



 ?>