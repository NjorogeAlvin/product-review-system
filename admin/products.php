<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth-check.php';
require_role('admin');
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Manage Products</h1>

<div id="add_buttons">
    <button>Add Products</button>
</div>

<div id="table">
    <table>
        <caption>Products</caption>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Specs</th>
            </tr>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>