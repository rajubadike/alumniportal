<?php
// Database Connection Settings
$host = 'localhost';
$dbname = 'events';
$username = 'root';
$password = '';

$successMessage = ''; // Initialize the success message

try {
    // Create a PDO database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $event = $_POST['event'];

    // Insert registration data into the database
    $stmt = $pdo->prepare("INSERT INTO registration (fullname, email, event) VALUES (:fullname, :email, :event)");
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':event', $event);

    if ($stmt->execute()) {
        // Registration successful, set the success message
        $successMessage = "Registration successful!";
    } else {
        // Registration failed, handle the error
        echo "Registration failed. Please try again.";
    }
}

// Close the database connection when you're done
$pdo = null;
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add CSS for the success message and animation */
        .success-message {
            display: none;
            font-size: 24px;
            color: green;
            text-align: center;
        }
        .tick-mark {
            animation: bounce 1s linear infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    <div class="success-message">
        Registration successful! <span class="tick-mark">✓</span>
    </div>
    
    <form action="" method="post">
        
    </form>

    <!-- JavaScript to show the success message -->
    <script>
        // Check if the success message is not empty, then display it
        var successMessage = "<?php echo $successMessage; ?>";
        if (successMessage !== '') {
            var successMessageElement = document.querySelector('.success-message');
            successMessageElement.style.display = 'block';
        }
    </script>
</body>
</html>
