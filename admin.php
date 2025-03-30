<?php
require_once 'config/database.php';
session_start();

// Simple authentication (in a real app, use proper authentication)
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// CRUD Operations
// Delete reservation
if (isset($_GET['delete'])) {
    try {
        $sql = "DELETE FROM reservations WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_GET['delete']]);
        $_SESSION['message'] = "Reservation deleted successfully.";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting reservation: " . $e->getMessage();
    }
    header("Location: admin.php");
    exit;
}

// Update reservation status
if (isset($_POST['update_status'])) {
    try {
        $sql = "UPDATE reservations SET status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST['status'], $_POST['reservation_id']]);
        $_SESSION['message'] = "Reservation status updated successfully.";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error updating reservation: " . $e->getMessage();
    }
    header("Location: admin.php");
    exit;
}

// Get all reservations
try {
    $sql = "SELECT * FROM reservations ORDER BY check_in DESC";
    $stmt = $pdo->query($sql);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("ERROR: Could not execute $sql. " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel 102 - Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Hotel 102 - Admin Panel</div>
            <ul>
                <li><a href="index.php">View Site</a></li>
                <li><a href="admin.php">Reservations</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="admin-content">
        <div class="container">
            <h1>Reservation Management</h1>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert success"><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest Name</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Room</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['id']) ?></td>
                        <td><?= htmlspecialchars($reservation['name']) ?></td>
                        <td><?= htmlspecialchars($reservation['check_in']) ?></td>
                        <td><?= htmlspecialchars($reservation['check_out']) ?></td>
                        <td><?= ucfirst($reservation['room_capacity']) ?> (<?= ucfirst($reservation['room_type']) ?>)</td>
                        <td>PHP <?= number_format($reservation['total'], 2) ?></td>
                        <td>
                            <form method="POST" action="admin.php" class="status-form">
                                <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?= $reservation['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="confirmed" <?= $reservation['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                    <option value="cancelled" <?= $reservation['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    <option value="completed" <?= $reservation['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <a href="admin_reservation_details.php?id=<?= $reservation['id'] ?>" class="btn">View</a>
                            <a href="admin.php?delete=<?= $reservation['id'] ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>