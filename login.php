<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database = "login_register";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Initialize variables for messages
$loginErrorMessage = "";
$redirect = false;

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user input
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if ($email === false) {
        $loginErrorMessage = "Invalid email address.";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password (you should use password_verify in production)
            if ($user && password_verify($password, $user['password_hash'])) {
                // Login successful
                $redirect = true;
            } else {
                // Handle incorrect password
                $loginErrorMessage = "Incorrect password.";
            }
        } else {
            // Handle non-existent user
            $loginErrorMessage = "User does not exist.";
        }
    }
}

$conn->close();

if ($redirect) {
    // Redirect to vel.html after successful login
    header("Location: vel.html");
    exit();
}
?>






