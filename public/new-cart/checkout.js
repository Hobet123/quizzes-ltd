    // Get the cart items from local storage
    let cartItems = JSON.parse(localStorage.getItem('cartItems'));

    let totalItems = 0;


    if(cartItems != null){
        totalItems = Object.keys(cartItems).length;
    }

    // Update the cart count in the cart bar
    let cartCount = document.getElementById('cart-count');

    cartCount.textContent = totalItems;
    
    // Generate the cart item list
    let cartItemsDiv = document.getElementById('cart-items');
    let totalPriceDiv = document.getElementById('total-price');

    let totalPrice = 0;
    
    for (let id in cartItems) {

        let item = cartItems[id];
        let productDiv = document.createElement('div');
        productDiv.classList.add('quiz', 'item');

        productDiv.innerHTML = `
            <p>${item.name} - Price: $${item.price}</p>
            <button onclick="removeFromCart('${id}')">Remove</button>
        `;
        cartItemsDiv.appendChild(productDiv);

        // Calculate the total price
        totalPrice += parseInt(item.price);
    }

    // console.log(totalPrice);

    // Update the total price display
    totalPriceDiv.innerHTML = `<p>Total: $${totalPrice.toFixed(2)}</p>`;

    // Function to remove a product from the cart
    function removeFromCart(id) {
        // Get the existing cart items from local storage
        let cartItems = JSON.parse(localStorage.getItem('cartItems'));

        // Remove the selected product from the cart
        delete cartItems[id];

        // Save the updated cart items to local storage
        localStorage.setItem('cartItems', JSON.stringify(cartItems));

        // Refresh the page to update the cart display
        location.reload();
    }