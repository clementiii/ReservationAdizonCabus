<?php

class AdminController
{

    // Helper function to load views (can be moved to a base controller later)
    // Consider creating a BaseController to avoid duplication
    private function loadView($viewName, $data = [], $includeLayout = true)
    {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            if ($includeLayout && file_exists(__DIR__ . '/../views/layout/header.php')) {
                require __DIR__ . '/../views/layout/header.php';
            }

            require $viewPath;

            if ($includeLayout && file_exists(__DIR__ . '/../views/layout/footer.php')) {
                require __DIR__ . '/../views/layout/footer.php';
            }
        } else {
            echo "Error: View '$viewName' not found.";
        }
    }

    // Check if admin is logged in
    private function checkAuth()
    {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            // Redirect to login page if not logged in
            header('Location: index.php?action=adminLogin');
            exit;
        }
    }

    // Show the admin login form
    public function showLogin()
    {
        // Don't include the main layout for the login page
        $this->loadView('admin_login', [], false);
    }

    // Process admin login attempt
    public function authenticate()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // **Security Note:** Storing credentials in a text file is highly insecure.
            // This should be replaced with database storage and hashed passwords.
            $credentialsPath = __DIR__ . '/../adminaccount.txt';
            if (file_exists($credentialsPath)) {
                $lines = file($credentialsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (count($lines) >= 2) {
                    $storedUser = trim(str_replace('Username: ', '', $lines[0]));
                    $storedPass = trim(str_replace('Password: ', '', $lines[1]));

                    // **Security Note:** Plain text password comparison is insecure.
                    if ($username === $storedUser && $password === $storedPass) {
                        // Login successful
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_username'] = $username;
                        header('Location: index.php?action=adminDashboard');
                        exit;
                    }
                }
            }

            // Login failed
            $_SESSION['login_error'] = 'Invalid username or password.';
            header('Location: index.php?action=adminLogin');
            exit;

        } else {
            // Redirect if not POST
            header('Location: index.php?action=adminLogin');
            exit;
        }
    }

    // Show the admin dashboard
    public function dashboard()
    {
        $this->checkAuth(); // Ensure admin is logged in

        // Fetch reservations using the Reservation model
        $reservationModel = new Reservation();
        $stmt = $reservationModel->readAll();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Load the dashboard view, passing the reservations data
        $this->loadView('admin_dashboard', ['reservations' => $reservations]);
    }

    // Log the admin out
    public function logout()
    {
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        header('Location: index.php?action=adminLogin'); // Redirect to login page
        exit;
    }

    // Add methods for updating/deleting reservations later
    // e.g., public function updateReservationStatus(), public function deleteReservation()

}
?>