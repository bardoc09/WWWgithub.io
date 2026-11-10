document.getElementById("login-form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent default form submission

    // Get user input values
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Dummy credentials for validation (Replace this with real authentication logic)
    const validUsername = "admin";
    const validPassword = "password";

    if (username === validUsername && password === validPassword) {
        alert("Login successful!");
        window.location.href = "dashboard.php"; // Redirect to the Dashboard
    } else {
        alert("Invalid username or password. Please try again.");
    }
});
