<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database = "login_register";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form input
    $email = filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat_password'];

    if ($email === false) {
        echo "Invalid email address.";
    } else if ($password === $repeatPassword) {
        // Hash the password for security (you should use password_hash in production)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO user (email, password_hash) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $email, $hashedPassword);
            if ($stmt->execute()) {
                // Registration successful
                header("Location: login.html");
                exit();
            } else {
                // Handle unsuccessful registration
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Handle SQL query preparation error
            echo "Error: " . $conn->error;
        }
    } else {
        // Handle password mismatch error
        echo "Passwords do not match.";
    }
}

$conn->close();
?>
