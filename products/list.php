<?php
require_once '../includes/auth-check.php';
require_once '../config/db.php';
include '../includes/header.php';

// Fetch products with average rating and review count (approved reviews only)
$sql = "SELECT p.product_id, p.name, p.brand, p.price, p.specs,
               ROUND(AVG(CASE WHEN r.status = 'approved' THEN r.rating END), 1) AS avg_rating,
               COUNT(CASE WHEN r.status = 'approved' THEN r.review_id END) AS review_count
        FROM products p
        LEFT JOIN reviews r ON r.product_id = p.product_id
        GROUP BY p.product_id, p.name, p.brand, p.price, p.specs
        ORDER BY p.name ASC";
$result = mysqli_query($conn, $sql);
?>

<h2>Products</h2>

<?php if (mysqli_num_rows($result) === 0): ?>
    <p class="empty">No products available yet.</p>
<?php else: ?>
    <div class="grid">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="card">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p class="brand"><?= htmlspecialchars($row['brand']) ?></p>
                <p class="desc"><?= htmlspecialchars(mb_strimwidth($row['specs'], 0, 100, '...')) ?></p>
                <div class="price">$<?= number_format($row['price'], 2) ?></div>

                <?php if ($row['review_count'] > 0): ?>
                    <div class="rating">&#9733; <?= $row['avg_rating'] ?>/5
                        (<?= $row['review_count'] ?> review<?= $row['review_count'] == 1 ? '' : 's' ?>)
                    </div>
                <?php else: ?>
                    <div class="no-rating">No reviews yet</div>
                <?php endif; ?>

                <a class="btn-accent" href="detail.php?id=<?= $row['product_id'] ?>">View Details</a>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
