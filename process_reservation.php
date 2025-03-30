<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session at the very top
session_start();

// Verify session data exists
if (!isset($_SESSION['reservation_data'])) {
    die("Session data missing. Please start your reservation again.");
}

require_once 'config/database.php';

try {
    // Prepare SQL statement
    $sql = "INSERT INTO reservations (
                name, address, contact, check_in, check_out, 
                room_capacity, room_type, payment_type, 
                rate, days, total, status
            ) VALUES (
                :name, :address, :contact, :check_in, :check_out, 
                :room_capacity, :room_type, :payment_type, 
                :rate, :days, :total, 'pending'
            )";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':name', $_SESSION['reservation_data']['name']);
    $stmt->bindParam(':address', $_SESSION['reservation_data']['address']);
    $stmt->bindParam(':contact', $_SESSION['reservation_data']['contact']);
    $stmt->bindParam(':check_in', $_SESSION['reservation_data']['check_in']);
    $stmt->bindParam(':check_out', $_SESSION['reservation_data']['check_out']);
    $stmt->bindParam(':room_capacity', $_SESSION['reservation_data']['room_capacity']);
    $stmt->bindParam(':room_type', $_SESSION['reservation_data']['room_type']);
    $stmt->bindParam(':payment_type', $_SESSION['reservation_data']['payment_type']);
    $stmt->bindParam(':rate', $_SESSION['reservation_data']['rate']);
    $stmt->bindParam(':days', $_SESSION['reservation_data']['days']);
    $stmt->bindParam(':total', $_SESSION['reservation_data']['total']);
    
    // Execute the query
    $stmt->execute();
    
    // Get the last inserted ID
    $reservation_id = $pdo->lastInsertId();
    
    // Clear session data
    unset($_SESSION['reservation_data']);
    
    // Display success message
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Reservation Confirmation</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f5f5f5; }
            .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            h1 { color: #4CAF50; }
            .btn { display: inline-block; padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Reservation Successful!</h1>
            <p>Your reservation has been submitted successfully.</p>
            <p><strong>Reservation ID:</strong> '.$reservation_id.'</p>
            <p>We will contact you shortly to confirm your booking details.</p>
            <a href="index.php" class="btn">Return to Home</a>
        </div>
    </body>
    </html>';
    
} catch(PDOException $e) {
    // Display error message
    echo '<div style="padding:20px;background:#ffebee;color:#c62828;border:1px solid #ef9a9a;border-radius:5px;margin:20px;">
            <h2>Error Processing Reservation</h2>
            <p>'.$e->getMessage().'</p>
            <p>Please try again or contact support if the problem persists.</p>
            <a href="index.php" style="color:#1565c0;">Return to Reservation Form</a>
          </div>';
}
?>