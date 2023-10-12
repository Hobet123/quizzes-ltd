
// var phoneInput = document.getElementById('phone');

// phoneInput.addEventListener('input', function (e) {
//   var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
//   e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
// });

    const passwordField = document.getElementById('password');
    const passwordInfo = document.querySelector('.password-info');

    passwordInfo.addEventListener('click', function() {

        if (passwordField.type === 'password') {
            
            console.log(passwordInfo);

            passwordField.type = 'text';
            passwordInfo.textContent = `hide`;

        } 
        else {

            console.log(passwordInfo);

            passwordField.type = 'password';
            passwordInfo.textContent = 'show';

        }
    });

    const passwordField2 = document.getElementById('password_confirmation');
    const passwordInfo2 = document.querySelector('.password-info2');

    passwordInfo2.addEventListener('click', function() {
        if (passwordField2.type === 'password') {
            
            console.log(passwordInfo2);

            passwordField2.type = 'text';
            passwordInfo2.textContent = `hide`;

        } 
        else {

            console.log(passwordInfo2);

            passwordField2.type = 'password';
            passwordInfo2.textContent = 'show';

        }
    });