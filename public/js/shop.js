

// Function to add a product to the cart
function addToCart(name, id, price, cover_image) {
    // Get the existing cart items from local storage
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || {};

    // Check if the selected product is already in the cart
    if (cartItems[id]) {
        // If so, display an alert message and return
        alert('This product is already in the cart!');
        return;
    }

    // Add the selected product to the cart
    cartItems[id] = { name, id, price, cover_image };

    // Save the updated cart items to local storage
    localStorage.setItem('cartItems', JSON.stringify(cartItems));

    // alert("The quiz was added to cart!");

   // console.log(localStorage);

    // Update the cart count in the cart bar
    cartCount.textContent = Object.keys(cartItems).length;

    window.location.href = "/cart";
    
    return true;
}
