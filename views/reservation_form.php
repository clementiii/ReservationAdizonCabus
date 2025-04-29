<?php
// This is views/reservation_form.php 
// It only contains the HTML for the reservation form section.
// Assumes the header and footer are loaded by the controller.

// Display error messages if they exist in the session
if (isset($_SESSION['error_message'])) {
    echo '<div class="error-message" style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']); // Clear the message after displaying
}
?>

<section id="reservation" class="reservation">
    <div class="container">
        <div class="header">
            <h1>Hotel Reservation System</h1>
        </div>

        <!-- Form action points to the front controller route -->
        <form method="POST" action="index.php?action=processReservation">
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

            <!-- Room Rates Table (Static display) -->
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
                <!-- The submit button no longer needs name="calculate" -->
                <input type="submit" value="Submit Reservation" class="btn">
                <input type="reset" value="Clear Entry" class="btn">
            </div>
        </form>
    </div>
</section>

<!-- Basic client-side validation (can be enhanced) -->
<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        const requiredFields = document.querySelectorAll('form [required]');
        let isValid = true;
        let firstInvalidField = null;

        requiredFields.forEach(field => {
            // Check if the field is visible and empty
            if (field.offsetParent !== null && !field.value.trim()) {
                // Find the associated label or use the field name
                let fieldName = field.name.replace(/_/g, ' ');
                if (field.previousElementSibling && field.previousElementSibling.tagName === 'LABEL') {
                    fieldName = field.previousElementSibling.textContent.replace(':', '');
                }
                alert(`Please fill in the ${fieldName} field.`);
                isValid = false;
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
            }
        });

        if (!isValid) {
            e.preventDefault(); // Stop form submission
            if (firstInvalidField) {
                firstInvalidField.focus(); // Focus the first invalid field
            }
        }
        // Add date validation if needed
        const checkIn = document.querySelector('input[name="check_in"]').value;
        const checkOut = document.querySelector('input[name="check_out"]').value;
        if (checkIn && checkOut && new Date(checkOut) <= new Date(checkIn)) {
            alert('Check-out date must be after check-in date.');
            isValid = false;
            e.preventDefault();
            document.querySelector('input[name="check_out"]').focus();
        }
    });
</script>