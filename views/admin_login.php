<?php
// This is views/admin_login.php
// Standalone login page, does not use the main header/footer layout.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hotel 102</title>
    <!-- Link to the main CSS file -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Add some specific styles for the login page */
        body {
            background-color: #f4f4f4;
            /* Light grey background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Raleway', sans-serif;
        }

        .login-container {
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 25px;
            color: #333;
        }

        .login-container .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            /* Include padding in width */
        }

        .login-container .btn {
            width: 100%;
            padding: 12px;
            background-color: #5cb85c;
            /* Green */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-container .btn:hover {
            background-color: #4cae4c;
        }

        .error-message {
            color: #d9534f;
            /* Red */
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Admin Login</h1>

        <?php
        // Display login errors if they exist
        if (isset($_SESSION['login_error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
            unset($_SESSION['login_error']); // Clear the error message
        }
        ?>

        <!-- Form action points to the admin authentication route -->
        <form method="POST" action="index.php?action=adminAuthenticate">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>

</html>