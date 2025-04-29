<?php
// This is views/reservation_success.php
// It displays the confirmation message after successful submission.
// Assumes the header and footer are loaded by the controller.
// Assumes $reservation_id is passed from the controller via loadView()
?>

<div class="container" style="text-align: center; padding: 40px;">
    <h1>Reservation Successful!</h1>
    <p>Your reservation has been submitted successfully.</p>
    <?php if (isset($reservation_id)): ?>
        <p><strong>Reservation ID:</strong> <?php echo htmlspecialchars($reservation_id); ?></p>
    <?php endif; ?>
    <p>We will contact you shortly to confirm your booking details.</p>
    <a href="index.php?action=home" class="btn" style="display: inline-block; margin-top: 20px;">Return to Home</a>
</div>