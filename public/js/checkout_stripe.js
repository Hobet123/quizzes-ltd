document.cookie = "m=value; SameSite=None; Secure";

// const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
const csrfToken =  document.body.dataset.csrfToken;
const userId = document.body.dataset.userId;

const emailAddress = document.body.dataset.userEmail;



// This is a public sample test API key.
const stripe = Stripe("pk_test_51MrHitIa7Ttd6va41l5BXxLTSAZPp4qhKd1ARh99oKaxD8s9VL9zxNeC0CfDFdwBBnSG2ujDESUSrqQz8Wht0V3D00YVXwWaVt");




// The items the customer wants to buy
// const items = [{ id: "xl-tshirt" }];

let elements;

initialize();
checkStatus();

document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);

// Fetches a payment intent and captures the client secret
async function initialize() {

//   console.log(csrfToken);
// console.log(userId);
// console.log(emailAddress);
  items.unshift(userId);
  
  console.log(items);

  // return;



//   }).then((r) => r.json());
    // return;

    const response = await fetch("https://nquiz.philipp.ink/payment_intent", {
        // method: "GET",
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ items }),
    });

    const result = await response.json();

    // Assuming the response structure is { "clientSecret": "SecretKey" }
    const clientSecret = result.clientSecret;

    elements = stripe.elements({ clientSecret });

    console.log(result);

    const paymentElementOptions = {
        layout: "tabs",
    };

    const paymentElement = elements.create("payment", paymentElementOptions);
    paymentElement.mount("#payment-element");
}

async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);

  const { error } = await stripe.confirmPayment({
    elements,
    confirmParams: {
      // Make sure to change this to your payment completion page
      return_url: "https://nquiz.philipp.ink/checkout_stripe",
      receipt_email: emailAddress,
    },
  });

  if (error.type === "card_error" || error.type === "validation_error") {
    showMessage(error.message);
  } else {
    showMessage("An unexpected error occurred.");
  }

  setLoading(false);
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    "payment_intent_client_secret"
  );

  if (!clientSecret) {
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  switch (paymentIntent.status) {
    case "succeeded":
      showMessage("Payment succeeded!");

      console.log(paymentIntent.id);

      confirmOrder(paymentIntent.id);
      
      break;
    case "processing":
      showMessage("Your payment is processing.");
      break;
    case "requires_payment_method":
      showMessage("Your payment was not successful, please try again.");
      break;
    default:
      showMessage("Something went wrong.");
      break;
  }
}

// ------- UI helpers -------

function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");
  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");
    messageContainer.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector("#submit").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("#submit").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#button-text").classList.remove("hidden");
  }
}

//Confirm Order
function confirmOrder(order_id) {
  fetch(`/confirmOrder?order_id=${order_id}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Failed to confirm order.');
      }
      return response.json();
    })
    .then(data => {
      // if (data === true) {

        if (cartItems !== null && cartItems !== undefined) {
          // Remove 'cartItems' from localStorage
          localStorage.removeItem('cartItems');
          console.log('cartItems removed from localStorage');
        } else {
          console.log('cartItems not found in localStorage');
        }

        alert("Your order has been proccessed!");

        window.location.href = '/myPage';

      // } 
      // else {
      //   alert('Order ID not found.');
      // }
    })
    .catch(error => alert('An error occurred while confirming the order. Try again!'));
}