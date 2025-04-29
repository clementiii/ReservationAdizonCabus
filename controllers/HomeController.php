<?php

class HomeController
{

    // Method to display the home page
    public function index()
    {
        // Any data needed for the home view can be prepared here
        // For now, we just load the view using the helper
        $this->loadView('home');
    }

    // Helper function to load views (can be moved to a base controller later)
    // Duplicated from ReservationController for now, consider a BaseController
    private function loadView($viewName, $data = [])
    {
        // Make data available to the view
        extract($data);

        // Construct the path to the view file
        $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            // Include a common layout/header
            if (file_exists(__DIR__ . '/../views/layout/header.php')) {
                require __DIR__ . '/../views/layout/header.php';
            }

            require $viewPath;

            // Include a common layout/footer
            if (file_exists(__DIR__ . '/../views/layout/footer.php')) {
                require __DIR__ . '/../views/layout/footer.php';
            }
        } else {
            // Handle view not found error
            echo "Error: View '$viewName' not found.";
        }
    }
}
?>