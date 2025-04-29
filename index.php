<?php
// Enable error reporting at the top
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
session_start();

// Function to calculate room rate
function calculateRoomRate($capacity, $type) {
    $rates = [
        'single' => [
            'regular' => 100.00,
            'deluxe' => 300.00,
            'suite' => 500.00
        ],
        'double' => [
            'regular' => 200.00,
            'deluxe' => 500.00,
            'suite' => 800.00
        ],
        'family' => [
            'regular' => 500.00,
            'deluxe' => 750.00,
            'suite' => 1000.00
        ]
    ];
    return $rates[strtolower($capacity)][strtolower($type)];
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculate'])) {
    $rate = calculateRoomRate($_POST['room_capacity'], $_POST['room_type']);
    $days = (strtotime($_POST['check_out']) - strtotime($_POST['check_in'])) / (60 * 60 * 24);
    $total = $rate * $days;

    // Apply payment type charges
    switch ($_POST['payment_type']) {
        case 'check':
            $total *= 1.05; // Add 5%
            break;
        case 'credit':
            $total *= 1.10; // Add 10%
            break;
        case 'cash':
            if ($days >= 6) {
                $total *= 0.85; // 15% discount
            } elseif ($days >= 3) {
                $total *= 0.90; // 10% discount
            }
            break;
    }

    $_SESSION['reservation_data'] = [
        'name' => $_POST['name'],
        'address' => $_POST['address'],
        'contact' => $_POST['contact'],
        'check_in' => $_POST['check_in'],
        'check_out' => $_POST['check_out'],
        'room_capacity' => $_POST['room_capacity'],
        'room_type' => $_POST['room_type'],
        'payment_type' => $_POST['payment_type'],
        'rate' => $rate,
        'days' => $days,
        'total' => $total
    ];
    
    header("Location: process_reservation.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel 102</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header and Navigation -->
    <header>
        <nav>
            <div class="logo">Hotel 102</div>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#profile">About</a></li>
                <li><a href="#reservation">Reservation</a></li>
                <li><a href="#contacts">Contact</a></li>
                <!-- <li><a href="admin_login.php">Admin</a></li> -->
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Hotel 102</h1>
            <p>Your perfect getaway destination. Book your stay with us today!</p>
            <a href="#reservation" class="btn">Make a Reservation</a>
        </div>
    </section>

    <!-- Company Profile Section -->
    <section class="profile" id="profile">
        <div class="container">
            <h2>About Hotel 102</h2>
            <p>Hotel 102 is a luxurious hotel located in the heart of the city.</p>
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reservation" class="reservation">
        <div class="container">
            <div class="header">
                <h1>Hotel Reservation System</h1>
            </div>

            <form method="POST" action="">
                <!-- Guest Information -->
                <h2>Guest Information</h2>
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" required>

                    <label>Address:</label>
                    <input type="text" name="address" required>

                    <label>Contact Number:</label>
                    <input type="tel" name="contact" required>
                </div>

                <!-- Reservation Details -->
                <h2>Reservation Details</h2>
                <div class="form-group">
                    <label>Check-in Date:</label>
                    <input type="date" name="check_in" required>

                    <label>Check-out Date:</label>
                    <input type="date" name="check_out" required>

                    <label>Room Capacity:</label>
                    <select name="room_capacity" required>
                        <option value="">Select Capacity</option>
                        <option value="single">Single</option>
                        <option value="double">Double</option>
                        <option value="family">Family</option>
                    </select>

                    <label>Room Type:</label>
                    <select name="room_type" required>
                        <option value="">Select Type</option>
                        <option value="regular">Regular</option>
                        <option value="deluxe">De Luxe</option>
                        <option value="suite">Suite</option>
                    </select>

                    <label>Payment Type:</label>
                    <select name="payment_type" required>
                        <option value="">Select Payment</option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="credit">Credit Card</option>
                    </select>
                </div>

                <!-- Room Rates Table -->
                <h2>Room Rates</h2>
                <table>
                    <tr>
                        <th>Room Capacity</th>
                        <th>Room Type</th>
                        <th>Rate/day</th>
                    </tr>
                    <tr>
                        <td rowspan="3">Single</td>
                        <td>Regular</td>
                        <td>100.00</td>
                    </tr>
                    <tr>
                        <td>De Luxe</td>
                        <td>300.00</td>
                    </tr>
                    <tr>
                        <td>Suite</td>
                        <td>500.00</td>
                    </tr>
                    <tr>
                        <td rowspan="3">Double</td>
                        <td>Regular</td>
                        <td>200.00</td>
                    </tr>
                    <tr>
                        <td>De Luxe</td>
                        <td>500.00</td>
                    </tr>
                    <tr>
                        <td>Suite</td>
                        <td>800.00</td>
                    </tr>
                    <tr>
                        <td rowspan="3">Family</td>
                        <td>Regular</td>
                        <td>500.00</td>
                    </tr>
                    <tr>
                        <td>De Luxe</td>
                        <td>750.00</td>
                    </tr>
                    <tr>
                        <td>Suite</td>
                        <td>1,000.00</td>
                    </tr>
                    </table>
<div class="buttons">
    <input type="submit" name="calculate" value="Submit Reservation" class="btn">
    <input type="reset" value="Clear Entry" class="btn">
</div>
</form>
</div>
</section>

<!-- Contacts Section -->
<section id="contacts" class="contacts">
<div class="container">
<h2>Contact Us</h2>
<ul>
<li><strong>Phone:</strong> +1 (123) 456-7890</li>
<li><strong>Email:</strong> info@hotel102.com</li>
</ul>
</div>
</section>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
const requiredFields = document.querySelectorAll('[required]');
let isValid = true;

requiredFields.forEach(field => {
if (!field.value.trim()) {
alert(`Please fill in ${field.previousElementSibling.textContent}`);
isValid = false;
}
});

if (!isValid) {
e.preventDefault();
}
});
</script>
</body>
</html>