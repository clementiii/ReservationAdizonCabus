<?php
// This is views/admin_dashboard.php
// Displays the list of reservations for the admin.
// Assumes the header and footer are loaded by the controller.
// Assumes $reservations array is passed from the AdminController.

// Add a check for the data existence
if (!isset($reservations)) {
    $reservations = [];
    echo "<p>No reservation data available.</p>";
}
?>

<section class="admin-dashboard">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Admin Dashboard - Reservations</h1>
            <a href="index.php?action=adminLogout" class="btn" style="background-color: #d9534f;">Logout</a>
        </div>

        <?php
        // Display admin messages/errors if they exist
        if (isset($admin_message)) {
            echo '<div class="alert success" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #d4edda; color: #155724;">' . htmlspecialchars($admin_message) . '</div>';
        }
        if (isset($admin_error)) {
            echo '<div class="alert error" style="padding: 15px; margin-bottom: 20px; border-radius: 5px; background-color: #f8d7da; color: #721c24;">' . htmlspecialchars($admin_error) . '</div>';
        }
        ?>

        <?php if (empty($reservations)): ?>
            <p>No reservations found.</p>
        <?php else: ?>
            <table class="reservations-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 8px;">ID</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Name</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Contact</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Check-in</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Check-out</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Room</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Total</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Status</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Submitted</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $res): ?>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($res['id']); ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($res['name']); ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($res['contact']); ?>
                            </td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($res['check_in']); ?>
                            </td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($res['check_out']); ?>
                            </td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                <?php echo htmlspecialchars(ucfirst($res['room_capacity']) . ' ' . ucfirst($res['room_type'])); ?>
                            </td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                <?php echo htmlspecialchars(number_format($res['total'], 2)); ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                <!-- Status Update Form -->
                                <form action="index.php?action=adminUpdateStatus" method="POST" class="status-form"
                                    style="margin: 0; display: inline-flex; align-items: center;">
                                    <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                                    <select name="status"
                                        style="padding: 3px; border-radius: 4px; border: 1px solid #ccc; margin-right: 5px;">
                                        <option value="pending" <?php echo (strtolower($res['status']) === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo (strtolower($res['status']) === 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="cancelled" <?php echo (strtolower($res['status']) === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn"
                                        style="padding: 3px 8px; font-size: 0.8em; background-color: #5bc0de;">Update</button>
                                </form>
                            </td>
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                <?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($res['created_at']))); ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                <!-- Delete Link -->
                                <a href="index.php?action=adminDeleteReservation&id=<?php echo $res['id']; ?>"
                                    class="btn delete"
                                    style="background-color: #dc3545; padding: 3px 8px; font-size: 0.8em; text-decoration: none; display: inline-block;"
                                    onclick="return confirm('Are you sure you want to delete reservation #<?php echo $res['id']; ?>?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>