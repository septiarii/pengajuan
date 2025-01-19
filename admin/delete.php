<?php
require("../admin/Konfig.php");  // Include database connection

// Start session to manage user login status
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Check if ID is passed via GET and validate it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID

    // Delete the pengajuan from the database
    $sql = "DELETE FROM pengajuan WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        
        // Execute the query and check for success
        if ($stmt->execute()) {
            // Redirect to the list page with success message
            header("Location: halaman.php?message=success");
            exit();
        } else {
            // Handle query execution error
            header("Location: halaman.php?message=error");
            exit();
        }
    } else {
        // Handle statement preparation error
        header("Location: halaman.php?message=error");
        exit();
    }
} else {
    // If ID is not passed or invalid, redirect with an error
    header("Location: halaman.php?message=invalid_id");
    exit();
}

?>
