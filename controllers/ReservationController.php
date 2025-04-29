<?php

class ReservationController
{

    // Method to display the reservation form view
    public function showForm()
    {
        // Any data needed for the form view can be prepared here
        // For now, we just load the view
        $this->loadView('reservation_form');
    }

    // Method to process the reservation form submission
    public function process()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Basic validation (more robust validation is recommended)
            $required_fields = ['name', 'address', 'contact', 'check_in', 'check_out', 'room_capacity', 'room_type', 'payment_type'];
            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    // Handle error - e.g., redirect back with an error message
                    $_SESSION['error_message'] = "Please fill in all required fields.";
                    // Redirect back to the form (adjust path if needed)
                    header('Location: index.php?action=showReservation');
                    exit;
                }
            }

            $reservation = new Reservation();

            try {
                // Calculate total cost and set reservation properties
                $reservation->calculateTotalCost($_POST);

                // Attempt to save the reservation to the database
                if ($reservation->create()) {
                    // Success: Redirect to a success page or show a success view
                    // Pass the reservation ID to the success view
                    $this->loadView('reservation_success', ['reservation_id' => $reservation->id]);
                } else {
                    // Handle database insertion error
                    $_SESSION['error_message'] = "Failed to save reservation. Please try again.";
                    header('Location: index.php?action=showReservation');
                    exit;
                }
            } catch (Exception $e) {
                // Handle calculation or validation errors from the model
                $_SESSION['error_message'] = "Error processing reservation: " . $e->getMessage();
                header('Location: index.php?action=showReservation');
                exit;
            }

        } else {
            // If not a POST request, redirect to the form
            header('Location: index.php?action=showReservation');
            exit;
        }
    }

    // Helper function to load views (can be moved to a base controller later)
    private function loadView($viewName, $data = [])
    {
        // Make data available to the view
        extract($data);

        // Construct the path to the view file
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            // Include a common layout/header if you have one
            if (file_exists(__DIR__ . '/../views/layout/header.php')) {
                require __DIR__ . '/../views/layout/header.php';
            }

            require $viewPath;

            // Include a common layout/footer if you have one
            if (file_exists(__DIR__ . '/../views/layout/footer.php')) {
                require __DIR__ . '/../views/layout/footer.php';
            }
        } else {
            // Handle view not found error
            echo "Error: View '$viewName' not found.";
            // You might want to load a 404 view here
        }
    }
}
?>