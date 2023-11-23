    let cartItems = JSON.parse(localStorage.getItem('cartItems'));
    // Generate the cart item list
    let cartItemsDiv = document.getElementById('cart-items');
    let totalPriceDiv = document.getElementById('total-price');

    let totalPrice = 0;

    let items = [];

    // console.log(cartItems);
    
    for (let id in cartItems) {

        let item = cartItems[id];

        let productDiv = document.createElement('div');
        productDiv.classList.add('row', 'item', 'p-3');

        productDiv.innerHTML = `
            <div class=""> - ${item.name}</div>
        `;

        cartItemsDiv.appendChild(productDiv);

        // Calculate the total price
        totalPrice += parseFloat(item.price);

        items.push(id);

    }

    // console.log(itemIDs);

    // Update the total price display
    totalPriceDiv.innerHTML = `$${totalPrice.toFixed(2)}`;
    

