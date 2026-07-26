<?php
// Starts the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If nobody is logged in, send them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: /product-review-system/auth/login.php");
    exit;
}

// Helper: restrict a page to admins only
// Call this at the top of any admin-only page: require_role('admin');
function require_role($role) {
    if ($_SESSION['role'] !== $role) {
        header("Location: /product-review-system/index.php");
        exit;
    }
}
?>