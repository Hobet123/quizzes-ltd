// if(!localStorage.getItem('cartItems')){
//     localStorage.setItem('cartItems', JSON.stringify([]));
//     console.log("here");
// }

// console.log(localStorage.getItem('cartItems'));

// const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

// const numItems = cartItems.length;

// console.log(numItems);


// // // Update the cart count in the cart bar
// let cartCount = document.getElementById("cart-count");
// cartCount.textContent = numItems;

    // Get the cart items from local storage
    let cartItems = JSON.parse(localStorage.getItem('cartItems'));

    let totalItems = 0;


    if(cartItems != null){
        totalItems = Object.keys(cartItems).length;
    }
//from car.js
        // Update the cart count in the cart bar
        let cartCount = document.getElementById('cart-count');

        cartCount.textContent = totalItems;
