let captcha;

function generate() {
  // Clear old input
  document.getElementById("submit").value = "";

  // Access the element to store the generated captcha
  captcha = document.getElementById("captcha-display");
  let uniquechar = "";

  const randomchar =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  // Generate captcha for length of 5 with random characters
  for (let i = 0; i < 5; i++) {
    // Start from 0 to generate 5 characters
    uniquechar += randomchar.charAt(
      Math.floor(Math.random() * randomchar.length)
    );
  }

  // Store generated input
  captcha.innerHTML = uniquechar;
}

function printmsg() {
  const usr_input = document.getElementById("submit").value;

  // Check whether the input is equal to generated captcha or not
  if (usr_input === captcha.innerHTML) {
    document.getElementById("key").innerHTML = "Matched";
  } else {
    document.getElementById("key").innerHTML = "Not Matched";
  }
}

// Add event listener to refresh button to call generate() function
document.querySelector(".refresh-button").addEventListener("click", generate);
