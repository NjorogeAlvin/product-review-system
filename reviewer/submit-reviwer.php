<?php
require_once '../includes/auth-check.php';
require_once '../config/db.php';

require_role('reviewer'); // only reviewers can access this page

$reviewer_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $title      = trim($_POST['title']);
    $rating     = intval($_POST['rating']);
    $comment    = trim($_POST['comment']);

    if ($product_id <= 0 || empty($title) || $rating < 1 || $rating > 5 || empty($comment)) {
        $error_msg = "Please select a product, add a title, a rating (1-5), and a comment.";
    } else {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO reviews (product_id, user_id, title, rating, comment, status, created_at)
             VALUES (?, ?, ?, ?, ?, 'pending', NOW())"
        );
        mysqli_stmt_bind_param($stmt, "iisis", $product_id, $reviewer_id, $title, $rating, $comment);

        if (mysqli_stmt_execute($stmt)) {
            $success_msg = "Review submitted successfully! It is now pending admin approval.";
        } else {
            $error_msg = "Error submitting review: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

// Products for the dropdown
$products = mysqli_query($conn, "SELECT product_id, name FROM products ORDER BY name ASC");

// This reviewer's past reviews
$my_reviews_stmt = mysqli_prepare($conn,
    "SELECT r.review_id, p.name AS product_name, r.title, r.rating, r.comment, r.status, r.created_at
     FROM reviews r
     JOIN products p ON p.product_id = r.product_id
     WHERE r.user_id = ?
     ORDER BY r.created_at DESC"
);
mysqli_stmt_bind_param($my_reviews_stmt, "i", $reviewer_id);
mysqli_stmt_execute($my_reviews_stmt);
$my_reviews = mysqli_stmt_get_result($my_reviews_stmt);

include '../includes/header.php';
?>

<h2>Submit a Product Review</h2>

<?php if ($success_msg): ?><div class="msg-success"><?= htmlspecialchars($success_msg) ?></div><?php endif; ?>
<?php if ($error_msg): ?><div class="msg-error"><?= htmlspecialchars($error_msg) ?></div><?php endif; ?>

<form method="POST" action="submit-review.php">
    <label for="product_id">Select Product</label>
    <select name="product_id" id="product_id" required>
        <option value="">-- Choose a product --</option>
        <?php while ($p = mysqli_fetch_assoc($products)): ?>
            <option value="<?= $p['product_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label for="title">Review Title</label>
    <input type="text" name="title" id="title" placeholder="Sum up your review" required>

    <label for="rating">Rating</label>
    <select name="rating" id="rating" required>
        <option value="">-- Select rating --</option>
        <option value="5">5 - Excellent</option>
        <option value="4">4 - Good</option>
        <option value="3">3 - Average</option>
        <option value="2">2 - Poor</option>
        <option value="1">1 - Very Poor</option>
    </select>

    <label for="comment">Comment</label>
    <textarea name="comment" id="comment" placeholder="Write your review here..." required></textarea>

    <input type="submit" value="Submit Review">
</form>

<h3 style="margin-top:35px;">My Submitted Reviews</h3>
<table>
    <tr><th>Product</th><th>Title</th><th>Rating</th><th>Comment</th><th>Status</th><th>Date</th></tr>
    <?php if (mysqli_num_rows($my_reviews) === 0): ?>
        <tr><td colspan="6">You haven't submitted any reviews yet.</td></tr>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($my_reviews)): ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= $row['rating'] ?>/5</td>
                <td><?= htmlspecialchars($row['comment']) ?></td>
                <td class="status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></td>
                <td><?= date("M j, Y", strtotime($row['created_at'])) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<?php include '../includes/footer.php'; ?>
