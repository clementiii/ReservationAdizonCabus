<?php
// This is views/home.php
// Contains the static content for the Home, About, and Contact sections.
// Assumes the header and footer are loaded by the controller.
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Hotel 102</h1>
        <p>Your perfect getaway destination. Book your stay with us today!</p>
        <!-- Link points to the reservation form route -->
        <a href="index.php?action=showReservation" class="btn">Make a Reservation</a>
    </div>
</section>

<!-- Company Profile Section -->
<section class="profile" id="profile">
    <div class="container">
        <h2>About Hotel 102</h2>
        <p>Hotel 102 is a luxurious hotel located in the heart of the city. We offer world-class amenities, exceptional
            service, and comfortable accommodations to make your stay unforgettable.</p>
        <!-- Add more profile content here -->
    </div>
</section>

<!-- Contacts Section -->
<section id="contacts" class="contacts">
    <div class="container">
        <h2>Contact Us</h2>
        <p>Have questions or want to book directly? Reach out to us!</p>
        <ul>
            <li><strong>Phone:</strong> +1 (123) 456-7890</li>
            <li><strong>Email:</strong> info@hotel102.com</li>
            <li><strong>Address:</strong> 123 Hotel Street, City Center, YourCountry</li>
        </ul>
        <!-- You could add a contact form here if needed -->
    </div>
</section>