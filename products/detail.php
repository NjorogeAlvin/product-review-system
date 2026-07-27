<?php
require_once '../includes/auth-check.php';
require_once '../config/db.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    die("Invalid product.");
}

// Fetch the product
$stmt = mysqli_prepare($conn, "SELECT product_id, name, brand, price, specs FROM products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$product) {
    die("Product not found.");
}

// Average rating + count (approved only)
$rating_stmt = mysqli_prepare($conn,
    "SELECT ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS review_count
     FROM reviews WHERE product_id = ? AND status = 'approved'"
);
mysqli_stmt_bind_param($rating_stmt, "i", $product_id);
mysqli_stmt_execute($rating_stmt);
$rating_data = mysqli_fetch_assoc(mysqli_stmt_get_result($rating_stmt));

// Approved reviews with reviewer name
$reviews_stmt = mysqli_prepare($conn,
    "SELECT r.title, r.rating, r.comment, r.created_at, u.name
     FROM reviews r
     JOIN users u ON u.user_id = r.user_id
     WHERE r.product_id = ? AND r.status = 'approved'
     ORDER BY r.created_at DESC"
);
mysqli_stmt_bind_param($reviews_stmt, "i", $product_id);
mysqli_stmt_execute($reviews_stmt);
$reviews = mysqli_stmt_get_result($reviews_stmt);

include '../includes/header.php';
?>

<a class="back-link" href="list.php">&larr; Back to Products</a>

<div class="product-card">
    <h2><?= htmlspecialchars($product['name']) ?></h2>
    <p class="brand"><?= htmlspecialchars($product['brand']) ?></p>
    <div class="price">$<?= number_format($product['price'], 2) ?></div>

    <?php if ($rating_data['review_count'] > 0): ?>
        <div class="avg-rating">&#9733; <?= $rating_data['avg_rating'] ?>/5
            based on <?= $rating_data['review_count'] ?> review<?= $rating_data['review_count'] == 1 ? '' : 's' ?>
        </div>
    <?php else: ?>
        <div class="avg-rating">No reviews yet</div>
    <?php endif; ?>

    <p class="description"><?= nl2br(htmlspecialchars($product['specs'])) ?></p>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'reviewer'): ?>
        <a class="btn-accent" href="../reviewer/submit-review.php?product_id=<?= $product['product_id'] ?>">Write a Review</a>
    <?php endif; ?>
</div>

<div class="reviews-section">
    <h3>Customer Reviews</h3>
    <?php if (mysqli_num_rows($reviews) === 0): ?>
        <p class="empty">No reviews yet for this product.</p>
    <?php else: ?>
        <?php while ($rev = mysqli_fetch_assoc($reviews)): ?>
            <div class="review">
                <strong><?= htmlspecialchars($rev['title']) ?></strong>
                <div class="stars">
                    <?= str_repeat('&#9733;', $rev['rating']) . str_repeat('&#9734;', 5 - $rev['rating']) ?>
                    (<?= $rev['rating'] ?>/5)
                </div>
                <p><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                <div class="meta">by <?= htmlspecialchars($rev['name']) ?> on <?= date("M j, Y", strtotime($rev['created_at'])) ?></div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
