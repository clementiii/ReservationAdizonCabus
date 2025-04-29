<?php
session_start(); // Start session for flash messages or user sessions

// Basic Autoloader (Consider using Composer for robust autoloading later)
spl_autoload_register(function ($class_name) {
    $controller_path = __DIR__ . '/../controllers/' . $class_name . '.php';
    $model_path = __DIR__ . '/../models/' . $class_name . '.php';

    if (file_exists($controller_path)) {
        require_once $controller_path;
    } elseif (file_exists($model_path)) {
        require_once $model_path;
    }
});

// Simple Routing
$action = $_GET['action'] ?? 'home'; // Default action is 'home'
$controllerName = '';
$methodName = '';

switch ($action) {
    // Reservation routes
    case 'showReservation':
        $controllerName = 'ReservationController';
        $methodName = 'showForm';
        break;
    case 'processReservation':
        $controllerName = 'ReservationController';
        $methodName = 'process';
        break;

    // --- Admin Routes ---
    case 'adminLogin':
        $controllerName = 'AdminController';
        $methodName = 'showLogin';
        break;
    case 'adminAuthenticate':
        $controllerName = 'AdminController';
        $methodName = 'authenticate';
        break;
    case 'adminDashboard':
        $controllerName = 'AdminController';
        $methodName = 'dashboard';
        break;
    case 'adminLogout':
        $controllerName = 'AdminController';
        $methodName = 'logout';
        break;
    case 'adminUpdateStatus': // New route for updating status
        $controllerName = 'AdminController';
        $methodName = 'updateReservationStatus';
        break;
    case 'adminDeleteReservation': // New route for deleting
        $controllerName = 'AdminController';
        $methodName = 'deleteReservation';
        break;

    // --- Home Route ---
    case 'home':
    default:
        $controllerName = 'HomeController';
        $methodName = 'index';
        break;
}

// Instantiate controller and call method if they exist
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $methodName)) {
        try {
            $controller->$methodName();
        } catch (Exception $e) {
            // Basic error handling - log this in a real app
            echo "An error occurred: " . $e->getMessage();
            // You might want to show a generic error view here
            // include __DIR__ . '/../views/error.php';
        }
    } else {
        // Handle: Method not found
        echo "Error: Action method '$methodName' not found in controller '$controllerName'.";
        // include __DIR__ . '/../views/404.php'; // Or show a 404 view
    }
} else {
    // Handle: Controller not found
    echo "Error: Controller '$controllerName' not found.";
    // include __DIR__ . '/../views/404.php'; // Or show a 404 view
}

?>