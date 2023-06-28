
    // Generate the cart item list
    let cartItemsDiv = document.getElementById('cart-items');
    let totalPriceDiv = document.getElementById('total-price');

    let totalPrice = 0;

    let itemsList = 'Quizzes - ';
    
    for (let id in cartItems) {

        let item = cartItems[id];
        let productDiv = document.createElement('div');
        productDiv.classList.add('row', 'item', 'p-3');

        productDiv.innerHTML = `
            <div class="col-md-2"><div class="card-image" style="background-image: url('/cover_images/${item.cover_image}');"></div></div>
            <div class="col-md-4">${item.name}</div>
            <div class="col-md-3">Price: $${item.price}</div>


        `;
        cartItemsDiv.appendChild(productDiv);

        // Calculate the total price
        totalPrice += parseFloat(item.price);

        console.log(item.name);

        itemsList += item.name + '. ';

    }

    // console.log(totalPrice);

    // Update the total price display
    totalPriceDiv.innerHTML = `$${totalPrice.toFixed(2)}`;
    
    //item_name


    //document.getElementById('item_name').value = itemsList;

    // console.log(document.getElementById('item_name').value);

    //document.getElementById('amount').value = totalPrice.toFixed(2);

