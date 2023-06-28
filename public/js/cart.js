
    // Generate the cart item list
    let cartItemsDiv = document.getElementById('cart-items');
    let totalPriceDiv = document.getElementById('total-price');

    let totalPrice = 0;
    
    for (let id in cartItems) {

        let item = cartItems[id];
        let productDiv = document.createElement('div');
        productDiv.classList.add('row', 'item', 'p-3');

        productDiv.innerHTML = `
            <div class="col-md-2"><div class="card-image" style="background-image: url('/cover_images/${item.cover_image}');"></div></div>
            <div class="col-md-4">${item.name}</div>
            <div class="col-md-3">Price: $${item.price}</div>

            <div class="col-md-3"><button class="btn btn-danger" onclick="removeFromCart('${id}')">Remove</button></div>
        `;
        cartItemsDiv.appendChild(productDiv);

        // Calculate the total price
        totalPrice += parseFloat(item.price);
    }

    // console.log(totalPrice);

    // Update the total price display
    totalPriceDiv.innerHTML = `$${totalPrice.toFixed(2)}`;

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