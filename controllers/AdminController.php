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

            // Add message display logic before including the view
            if (isset($_SESSION['admin_message'])) {
                $data['admin_message'] = $_SESSION['admin_message'];
                unset($_SESSION['admin_message']);
                extract($data); // Re-extract to make message available
            }
            if (isset($_SESSION['admin_error'])) {
                $data['admin_error'] = $_SESSION['admin_error'];
                unset($_SESSION['admin_error']);
                extract($data); // Re-extract to make error available
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
            $credentialsPath = __DIR__ . '/../adminaccount.txt';
            if (file_exists($credentialsPath)) {
                $lines = file($credentialsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (count($lines) >= 2) {
                    // Correctly parse the actual file format "user: value" and "password: value"
                    $storedUserLine = explode(':', $lines[0], 2);
                    $storedPassLine = explode(':', $lines[1], 2);

                    if (count($storedUserLine) === 2 && count($storedPassLine) === 2) {
                        $storedUser = trim($storedUserLine[1]);
                        $storedPass = trim($storedPassLine[1]);

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

    // Update Reservation Status
    public function updateReservationStatus()
    {
        $this->checkAuth();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;
            $validStatuses = ['pending', 'confirmed', 'cancelled']; // Define valid statuses

            if ($id && $status && in_array(strtolower($status), $validStatuses)) {
                $reservationModel = new Reservation();
                if ($reservationModel->updateStatus($id, $status)) {
                    $_SESSION['admin_message'] = "Reservation #$id status updated successfully to '$status'.";
                } else {
                    $_SESSION['admin_error'] = "Failed to update status for reservation #$id.";
                }
            } else {
                $_SESSION['admin_error'] = "Invalid data provided for status update.";
            }
        } else {
            $_SESSION['admin_error'] = "Invalid request method for updating status.";
        }
        // Redirect back to the dashboard regardless of success or failure
        header('Location: index.php?action=adminDashboard');
        exit;
    }

    // Delete Reservation
    public function deleteReservation()
    {
        $this->checkAuth();
        // Use GET for simplicity here, but POST with CSRF token is safer
        $id = $_GET['id'] ?? null;

        if ($id) {
            $reservationModel = new Reservation();
            if ($reservationModel->delete($id)) {
                $_SESSION['admin_message'] = "Reservation #$id deleted successfully.";
            } else {
                $_SESSION['admin_error'] = "Failed to delete reservation #$id.";
            }
        } else {
            $_SESSION['admin_error'] = "No reservation ID provided for deletion.";
        }
        // Redirect back to the dashboard
        header('Location: index.php?action=adminDashboard');
        exit;
    }

}
?>