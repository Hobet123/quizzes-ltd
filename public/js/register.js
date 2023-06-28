

let myString = "";

for (let id in cartItems) {

    myString+= id + ',';
}

console.log(myString);
// Define the string to send in the AJAX post request

// Define the AJAX post request
const xhr = new XMLHttpRequest();
const url = `/addSessions?param1=${myString}`;
xhr.open('GET', url, true);

// Define the function to handle the AJAX response
xhr.onreadystatechange = function() {
  if (xhr.readyState === 4) {
    if (xhr.status === 200) {
      // Log the response string to the console
      console.log(xhr.responseText);

      //remove items from localstorage
      localStorage.clear();

      cartCount.textContent = 0;

    } else {
      // Handle the error
      console.error('Error:', xhr.statusText);
    }
  }
};

// Send the AJAX post request with the data
xhr.send();
