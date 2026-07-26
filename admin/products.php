<?php
require_once __DIR__ . '/../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="/Projects/product-review-system/assets/products.css">
</head>
<body>
    <h1>Products Management</h1>
    <div id="nav_bar_products">
        <nav>
            <ul>
                <li><a class="active" href="products.php">Products</a></li>
                <li><a href="moderate.php">Moderate</a></li>
            </ul>
        </nav>
    </div>
    <div id="add_buttons">
        <button>Add Products</button>
    </div>
    <div id="table">
        <table border="1">
            <caption>Products</caption>
            <thead>
                <div id="product_head">
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Specs</th>
                    </tr>
                </div>
            </thead>
            <tbody>
                <tr>
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