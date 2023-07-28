<?php

    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $currentURL = $scheme . "://" . $host . $_SERVER['REQUEST_URI'];
    $currentURL = str_replace('/paypal.php', '', $currentURL);
// echo $currentURL;

?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div>
    <?php
        session_start();
        $userId = $_SESSION['user_id'];
    ?>
    </div>
    <div id="paypal-button-container" style="width: 90%;"></div>

    <!--sandbox-->
    <!-- <script src="https://www.paypal.com/sdk/js?client-id=AQ2loKJZ3saKh4Zcgh01v6N4YIKLjCMoCmG10nnMP6ItnKOMUEL2PvPk8acs8eoW2K0W3vqGHFz036EF&currency=CAD"></script> -->
    
    <!--mark live-->
    <script src="https://www.paypal.com/sdk/js?client-id=AX2TKexVvqWrG0ieN8qGeGw4nxCBuOjY8benWjs84Mm6Z_CfxKjXNtsA6Y-4xHuRMFBa55Cxqakyhq4i&currency=CAD"></script>
    <script>
        // Set up the PayPal SDK with your client ID
        let cartItems = JSON.parse(localStorage.getItem('cartItems'));

        let totalPrice = 0;
        let itemsList = '';

        // console.log(cartItems);
        
        
        for (let id in cartItems) {

            let item = cartItems[id];

            totalPrice += parseFloat(item.price);
            itemsList += item.id + '.';
        }

        let userId = "<?php echo $userId;?>";

        let description = userId + "-" + itemsList;

        console.log(description);

        console.log('<?php echo $currentURL;?>');

        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: totalPrice, // The amount you want to charge
                            currency_code: 'CAD' // The currency code
                        },
                        description: description, // Optional purchase unit description
                        custom_id: 12345, // Optional purchase unit ID
                        reference_id: 'ref_123456' // Optional purchase unit reference ID
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Payment is successful
                    // You can perform further actions here, like redirecting the user to a success page or saving the transaction details
                    // console.log(details.purchase_units[0].description);
                    // console.log(details.purchase_units[0].amount);

                    alert('Your payment has been processed!');

                    let description = details.purchase_units[0].description;

                    //remove items from localstorage
                    localStorage.clear();

                    if (window.self !== window.top) {
                        // Redirect the parent page
                        window.top.location.href = "<?php echo $currentURL; ?>/addSessions?param1=" + description;
                    }
                    else{
                        window.location.href = "<?php echo $currentURL; ?>/addSessions?param1=" + description;
                    }
                    
                });
            }
        }).render('#paypal-button-container'); // Replace with the ID of your container element
    </script>
            <!-- 
/////////////////////////////////////
            4214027771329288
            0124
            250
            //submit-button
-->
</body>
</html>
