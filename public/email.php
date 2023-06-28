<?php
    $to      = 'paul.ph227@gmail.com';
    $subject = 'the subject';
    $message = 'hello';
    $headers = 'From: paul.ph227@gmail.com' . "\r\n" .
        'Reply-To: paul.ph227@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

    echo "mail";
?>