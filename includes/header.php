<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$loggedIn = isset($_SESSION['user_id']);
$role     = $_SESSION['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Review System</title>
<link rel="stylesheet" href="/product-review-system/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="/product-review-system/index.php" class="logo">ReviewHub</a>

        <nav class="main-nav">
            <a href="/product-review-system/products/list.php">Products</a>

            <?php if ($loggedIn && $role === 'admin'): ?>
                <a href="/product-review-system/admin/products.php">Manage Products</a>
                <a href="/product-review-system/admin/moderate.php">Moderate Reviews</a>
            <?php endif; ?>

            <?php if ($loggedIn && $role === 'reviewer'): ?>
                <a href="/product-review-system/reviewer/submit-reviwer.php">Write a Review</a>
            <?php endif; ?>

            <?php if ($loggedIn): ?>
                <a href="/product-review-system/auth/logout.php" class="btn-accent">Logout</a>
            <?php else: ?>
                <a href="/product-review-system/auth/login.php">Login</a>
                <a href="/product-review-system/auth/register.php" class="btn-accent">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="site-main">