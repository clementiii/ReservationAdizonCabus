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
        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9em;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="admin-login-page">
    <div class="login-container">
        <h1>Admin Login</h1>

        <?php
        // Display login errors if they exist
        if (isset($_SESSION['login_error'])) {
            echo '<div class="alert error">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
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

        <!-- Add a link back to the homepage -->
        <a href="index.php?action=home" class="back-link">Back to Home</a>
    </div>
</body>

</html>