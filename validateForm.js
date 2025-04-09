function validateForm() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var address = document.getElementById('address').value;
    var cardNumber = document.getElementById('cardNumber').value;
    var expirationDate = document.getElementById('expirationDate').value;
    var cvv = document.getElementById('cvv').value;
    var billingAddress = document.getElementById('billingAddress').value;

    // Check if any of the required fields are empty
    if (name === '' || email === '' || address === '' || cardNumber === '' || expirationDate === '' || cvv === '' || billingAddress === '') {
        alert('Please fill in all fields.');
        return false;
    }

    // Card Number Format Validation
    var cardNumberPattern = /^\d{16}$/; // 16 digits
    if (!cardNumber.match(cardNumberPattern)) {
        alert('Please enter a valid card number.');
        return false;
    }

    // Expiration Date Format Validation
    var expirationDatePattern = /^(0[1-9]|1[0-2])\/\d{2}$/; // MM/YY format
    if (!expirationDate.match(expirationDatePattern)) {
        alert('Please enter a valid expiration date in MM/YY format.');
        return false;
    }

    // CVV Format Validation
    var cvvPattern = /^\d{3}$/; // 3 digits
    if (!cvv.match(cvvPattern)) {
        alert('Please enter a valid CVV.');
        return false;
    }


    return true;
}
