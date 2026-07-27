<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth-check.php';
require_role('admin');

if (isset($_GET['action']) && isset($_GET['review_id'])) {
    $review_id = (int) $_GET['review_id'];

    if ($_GET['action'] === 'approve') {
        $update = mysqli_prepare($conn, "UPDATE reviews SET status = 'approved' WHERE review_id = ?");
        mysqli_stmt_bind_param($update, "i", $review_id);
        mysqli_stmt_execute($update);
    }

    if ($_GET['action'] === 'delete') {
        $delete = mysqli_prepare($conn, "DELETE FROM reviews WHERE review_id = ?");
        mysqli_stmt_bind_param($delete, "i", $review_id);
        mysqli_stmt_execute($delete);
    }

    header('Location: moderate.php');
    exit;
}

$sql = "SELECT reviews.review_id, reviews.title, reviews.rating, reviews.comment, reviews.status,
               products.product_id, products.name AS product_name,
               users.user_id, users.name AS reviewer_name
        FROM reviews
        JOIN products ON reviews.product_id = products.product_id
        JOIN users ON reviews.user_id = users.user_id
        WHERE reviews.status = 'pending'";

$result = mysqli_query($conn, $sql);

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
            <?php if (mysqli_num_rows($result) === 0): ?>
                <tr><td colspan="8">No pending reviews.</td></tr>
            <?php else: ?>
                <?php while ($review = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $review['review_id'] ?></td>
                        <td><?= $review['product_name'] ?></td>
                        <td><?= $review['reviewer_name'] ?></td>
                        <td><?= htmlspecialchars($review['title']) ?></td>
                        <td><?= $review['rating'] ?></td>
                        <td><?= htmlspecialchars($review['comment']) ?></td>
                        <td class="status-<?= $review['status'] ?>"><?= $review['status'] ?></td>
                        <td class="actions">
                            <a href="moderate.php?action=approve&review_id=<?= $review['review_id'] ?>" class="approve">Approve</a>
                            <a href="moderate.php?action=delete&review_id=<?= $review['review_id'] ?>" class="delete" onclick="return confirm('Delete this review? This cannot be undone.');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>