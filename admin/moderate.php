<?php
require_once __DIR__ . '/../config/db.php';
// require_once __DIR__ . '/../includes/auth-check.php';
// require_role('admin');
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Review Moderation</h1> 

<div id="table">
    <table>
        <caption>Moderation</caption>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>