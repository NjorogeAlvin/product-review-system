<?php
require_once __DIR__ . '/../config/db.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate</title>
    <link rel="stylesheet" href="/Projects/product-review-system/assets/moderate.css">
</head>
<body>
    <h1>Review Moderation</h1>
    <nav>
        <ul>
            <li><a href="products.php">Products</a></li>
            <li><a class ="active" href="moderate.php">Moderate</a></li>
        </ul>
    </nav>
        <div id="table">
        <table border="1">
            <caption>Modeartion</caption>
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Product ID</th>
                    <th>User ID</th>
                    <th>Title</th>
                    <th>Ratings</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php

?>