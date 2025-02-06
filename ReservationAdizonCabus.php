<?php
// Function to calculate room rate
function calculateRoomRate($capacity, $type)
{
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total = 0;
    if (isset($_POST['calculate'])) {
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
                // Apply cash discounts
                if ($days >= 6) {
                    $total *= 0.85; // 15% discount
                } elseif ($days >= 3) {
                    $total *= 0.90; // 10% discount
                }
                break;
        }
    }
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
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');

        /* Define variables */
        :root {
            --text: #0f0f0f;
            --background: #faf9f8;
            --primary: #b3865b;
            --secondary: #e0ba96;
            --accent: #e5a365;

        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        * {
            margin: 0;
            line-height: calc(1em + 0.5rem);
        }

        /* General Styles */
        body {
            font-family: "Raleway", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background);
            color: var(--text);
        }

        /* Header and Navigation */
        header {
            background: var(--background);
            padding: 1.5em;
            position: sticky;
            top: 0;
            z-index: 100;

        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--primary);
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav li {
            margin-left: 20px;
            /* Space between links */
        }

        nav a {
            text-decoration: none;
            color: var(--text);
            /* Or a different color */
            transition: color 0.3s ease;
            /* Smooth color transition */
        }

        nav a:hover {
            color: var(--primary);
            /* Highlight on hover */
        }

        /* Hero Section */
        .hero {
            position: relative;
            /* Important: Needed for positioning the pseudo-element */
            background: url('https://images.pexels.com/photos/225674/pexels-photo-225674.jpeg?auto=compress&cs=tinysrgb&w=1920') no-repeat center center/cover;
            background-color: var(--primary);
            color: var(--background);
            text-align: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--primary);
            opacity: 0.5;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin: 0 auto;
        }

        .hero-content h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }

        .hero-content .btn {
            background: var(--primary);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .hero-content .btn:hover {
            background: var(--secondary);
        }

        /* Profile Section */
        .profile {
            background: var(--background);
            padding: 40px 0;
            text-align: center;
        }

        .profile h2 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .profile p {
            font-size: 1rem;
            line-height: 1.6;
            max-width: 600px;
            margin: 1em auto;
        }

        /* Reservation Section */
        .reservation {
            padding: 40px 0;
            background-color: white;
            display: flex;
            justify-content: center;
        }

        .reservation .container {
            width: 60%;
        }

        .reservation .header h1 {
            color: var(--primary);
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        form h2 {
            color: var(--primary);
            margin-bottom: 0.5em;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            color: var(--background);
            padding: 10px 20px;
            margin-right: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .btn[type="submit"] {
            background: var(--primary);
        }

        .btn[type="submit"]:hover {
            background: var(--secondary);
        }

        .btn[type="reset"] {
            background: #dc3545;
            color: white;
        }

        .btn[type="reset"]:hover {
            background: #c82333;
        }

        .billing {
            margin-top: 20px;
            padding: 20px;
            background: var(--background);
            border-radius: 5px;
        }

        .billing h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .billing p {
            margin: 10px 0;
        }

        /* Contacts Section */
        .contacts {
            background: var(--primary);
            color: var(--background);
            padding: 40px 0;
            text-align: center;
        }

        .contacts h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .contacts ul {
            list-style: none;
            padding: 0;
        }

        .contacts ul li {
            font-size: 1rem;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <header>
        <nav>
            <div class="logo">Hotel 102</div>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Hotel 102</h1>
            <p>Your perfect getaway destination. Book your stay with us today!</p>
            <a href="#reservation" class="btn">Make a Reservation</a>
        </div>
    </section>

    <!-- Company Profile Section -->
    <section class="profile">
        <div class="container">
            <h2>About Hotel 102</h2>
            <p>Hotel 102 is a luxurious hotel located in the heart of the city. We offer world-class amenities, spacious
                rooms, and exceptional service to ensure a memorable stay for our guests. Whether you're here for
                business or leisure, Hotel 102 is your home away from home.</p>
            <p>Our mission is to provide an unforgettable experience with a blend of comfort, elegance, and convenience.
            </p>
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reservation" class="reservation">
        <div class="container">
            <div class="header">
                <h1>Hotel Reservation System</h1>
            </div>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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

                <?php if (isset($_POST['calculate'])): ?>
                    <!-- Billing Information -->
                    <div class="billing">
                        <h2>Billing Information</h2>
                        <p><strong>Room Rate:</strong> PHP
                            <?php echo number_format(calculateRoomRate($_POST['room_capacity'], $_POST['room_type']), 2); ?>
                            per day</p>
                        <p><strong>Number of Days:</strong> <?php echo $days; ?></p>
                        <p><strong>Payment Type:</strong> <?php echo ucfirst($_POST['payment_type']); ?></p>
                        <?php if ($_POST['payment_type'] == 'check'): ?>
                            <p><strong>Additional Charge:</strong> 5%</p>
                        <?php elseif ($_POST['payment_type'] == 'credit'): ?>
                            <p><strong>Additional Charge:</strong> 10%</p>
                        <?php elseif ($_POST['payment_type'] == 'cash' && $days >= 3): ?>
                            <p><strong>Discount:</strong> <?php echo ($days >= 6) ? '15%' : '10%'; ?></p>
                        <?php endif; ?>
                        <p><strong>Total Amount:</strong> PHP <?php echo number_format($total, 2); ?></p>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <!-- Contacts Section -->
    <section class="contacts">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Have questions or need assistance? Reach out to us!</p>
            <ul>
                <li><strong>Phone:</strong> +1 (123) 456-7890</li>
                <li><strong>Email:</strong> info@hotel102.com</li>
                <li><strong>Address:</strong> 102 West Rembo, Taguig City</li>
            </ul>
        </div>
    </section>
</body>

</html>