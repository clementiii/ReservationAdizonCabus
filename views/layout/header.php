<?php
// This is views/layout/header.php 
// It contains the common HTML head, navigation, etc.
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel 102 - MVC</title>
    <!-- Correct path to CSS file inside the public directory -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Header and Navigation -->
    <header>
        <nav>
            <div class="logo">Hotel 102</div>
            <ul>
                <!-- Update links to use the front controller -->
                <li><a href="index.php?action=home">Home</a></li>
                <li><a href="index.php?action=home#profile">About</a></li>
                <!-- Assuming profile is part of home view -->
                <li><a href="index.php?action=showReservation">Reservation</a></li>
                <li><a href="index.php?action=home#contacts">Contact</a></li>
                <!-- Assuming contacts is part of home view -->
                <li><a href="index.php?action=adminLogin">Admin</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content area where specific views will be loaded -->
    <main>