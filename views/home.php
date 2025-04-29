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
        <p>Nestled in the vibrant heart of the city, Hotel 102 offers a sanctuary of luxury and comfort. We pride
            ourselves on providing world-class amenities, exceptional, personalized service, and elegantly appointed
            accommodations designed to make your stay truly unforgettable. Whether you're here for business or leisure,
            our prime location offers convenient access to major attractions and business hubs.</p>
        <div class="features"> <!-- Added a div for potential feature styling -->
            <h3>Why Choose Us?</h3>
            <ul>
                <li><i class="fas fa-star"></i> Prime City Center Location</li>
                <li><i class="fas fa-concierge-bell"></i> Exceptional 24/7 Concierge Service</li>
                <li><i class="fas fa-utensils"></i> Gourmet Dining Options On-site</li>
                <li><i class="fas fa-wifi"></i> Complimentary High-Speed Wi-Fi</li>
                <li><i class="fas fa-spa"></i> State-of-the-art Fitness Center & Spa</li>
                <li><i class="fas fa-shuttle-van"></i> Free Airport Shuttle Service</li>
            </ul>
        </div>
    </div>
</section>

<!-- Contacts Section -->
<section id="contacts" class="contacts">
    <div class="container">
        <h2>Get In Touch</h2>
        <p>Have questions, need assistance, or want to book directly? Our team is ready to help!</p>
        <div class="contact-details"> <!-- Wrapper for layout -->
            <div class="contact-info">
                <h3>Contact Information</h3>
                <ul class="contact-list">
                    <li>
                        <i class="fas fa-phone"></i>
                        <strong>Phone:</strong>&nbsp;
                        <a href="tel:+11234567890" aria-label="Call Hotel 102">+1 (123) 456-7890</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <strong>Email:</strong>&nbsp;
                        <a href="mailto:info@hotel102.com" aria-label="Email Hotel 102">info@hotel102.com</a>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <strong>Address:</strong>&nbsp;
                        <address style="display:inline;">123 Southside, Taguig City, Philippines</address>
                    </li>
                </ul>
                <!-- Placeholder for Social Media Links -->
                <div class="social-media">
                    <h4>Follow Us:</h4>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="contact-map"> <!-- Placeholder for map -->
                <h3>Find Us</h3>
                <!-- Replace the placeholder div with the Google Map iframe -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.859068297335!2d121.06086729999998!3d14.5500507!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c9007355f6f1%3A0x1aa5e7e283cfe79!2zQXB5b25n4oCZcw!5e0!3m2!1sen!2sph!4v1745894936338!5m2!1sen!2sph"
                    width="100%" height="250" style="border:0; border-radius: 5px;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <!-- You could still add a contact form here if needed -->
    </div>
</section>