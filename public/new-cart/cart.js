
// Get the cart items from local storage
let cartItems = JSON.parse(localStorage.getItem('cartItems'));

let totalItems = 0;


if(cartItems != null){
    totalItems = Object.keys(cartItems).length;
}

console.log(totalItems);

// Update the cart count in the cart bar
let cartCount = document.getElementById('cart-count');

cartCount.textContent = totalItems;

// Function to add a product to the cart
function addToCart(name, id, price) {
    // Get the existing cart items from local storage
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || {};

    // Check if the selected product is already in the cart
    if (cartItems[id]) {
        // If so, display an alert message and return
        alert('This product is already in the cart!');
        return;
    }

    // Add the selected product to the cart
    cartItems[id] = { name, id, price };

    // Save the updated cart items to local storage
    localStorage.setItem('cartItems', JSON.stringify(cartItems));

    // Update the cart count in the cart bar
    cartCount.textContent = Object.keys(cartItems).length;
    
    return true;
}
