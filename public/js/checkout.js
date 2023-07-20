
    // Generate the cart item list
    let cartItemsDiv = document.getElementById('cart-items');
    let totalPriceDiv = document.getElementById('total-price');

    let userId = document.getElementById('userId').value;

    console.log(userId);

    let totalPrice = 0;

    let itemsList = 'Quizzes - ';

    let itemIDs = '';
    
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

        console.log(item.id);

        itemsList += item.name + '. ';

        itemIDs += item.id + '.';

    }
    if(totalPrice == 0 ){

        console.log(userId + "-" + itemIDs);

        let description = userId + "-" + itemIDs;
        //
        var form = document.getElementById("form-quiz");
        // form.action = "/addSessions?param1=" + description;
        //
        var param1 = document.getElementById("param1");
        param1.value = description;
        //
        var div = document.getElementById("show_free_quiz");
        div.style.display = "block"; // or

    }
    else{
        var div = document.getElementById("show_paypal");
        div.style.display = "block"; // or
    }

    console.log(totalPrice);

    // Update the total price display
    totalPriceDiv.innerHTML = `$${totalPrice.toFixed(2)}`;
    

