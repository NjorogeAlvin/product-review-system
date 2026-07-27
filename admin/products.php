<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth-check.php';
require_role('admin');

$showForm = isset($_GET['action']) && $_GET['action'] === 'add';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $specs = $_POST['specs'];

    if (is_numeric($price)) {
        $insert = mysqli_prepare($conn, "INSERT INTO products (name, brand, price, specs) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert, "ssds", $name, $brand, $price, $specs);
        mysqli_stmt_execute($insert);
        header('Location: products.php');
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['product_id'])) {
    $product_id = (int) $_GET['product_id'];

    $delete = mysqli_prepare($conn, "DELETE FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($delete, "i", $product_id);
    mysqli_stmt_execute($delete);

    header('Location: products.php');
    exit;
}

$editProduct = null;

if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['product_id'])) {
    $edit_id = (int) $_GET['product_id'];

    $editQuery = mysqli_prepare($conn, "SELECT product_id, name, brand, price, specs FROM products WHERE product_id = ?");
    mysqli_stmt_bind_param($editQuery, "i", $edit_id);
    mysqli_stmt_execute($editQuery);
    $editResult = mysqli_stmt_get_result($editQuery);
    $editProduct = mysqli_fetch_assoc($editResult);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $update_id = (int) $_POST['product_id'];
    $name  = $_POST['name'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $specs = $_POST['specs'];

    if (is_numeric($price)) {
        $update = mysqli_prepare($conn, "UPDATE products SET name = ?, brand = ?, price = ?, specs = ? WHERE product_id = ?");
        mysqli_stmt_bind_param($update, "ssdsi", $name, $brand, $price, $specs, $update_id);
        mysqli_stmt_execute($update);
        header('Location: products.php');
        exit;
    }
}

$sql = "SELECT product_id, name, brand, price, specs FROM products";
$result = mysqli_query($conn, $sql);

require_once __DIR__ . '/../includes/header.php';
?>

<h1>Manage Products</h1>

<div id="add_buttons">
    <a href="products.php?action=add" id="addbtn">+ Add Product</a>
</div>

<?php if ($showForm || $editProduct): ?>
    <form method="POST" action="products.php">
        <?php if ($editProduct): ?>
            <input type="hidden" name="product_id" value="<?= $editProduct['product_id'] ?>">
        <?php endif; ?>

        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= $editProduct ? htmlspecialchars($editProduct['name']) : '' ?>" required>

        <label for="brand">Brand</label>
        <input type="text" name="brand" id="brand" value="<?= $editProduct ? htmlspecialchars($editProduct['brand']) : '' ?>" required>

        <label for="price">Price</label>
        <input type="text" name="price" id="price" value="<?= $editProduct ? $editProduct['price'] : '' ?>" required>

        <label for="specs">Specs</label>
        <textarea name="specs" id="specs"><?= $editProduct ? htmlspecialchars($editProduct['specs']) : '' ?></textarea>

        <input type="submit" value="<?= $editProduct ? 'Update Product' : 'Save Product' ?>">
    </form>
<?php endif; ?>

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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) === 0): ?>
                <tr><td colspan="6">No products yet.</td></tr>
            <?php else: ?>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $product['product_id'] ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['brand']) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td><?= htmlspecialchars($product['specs']) ?></td>
                        <td class="actions">
                            <a href="products.php?action=edit&product_id=<?= $product['product_id'] ?>" class="edit">Edit</a>
                            <a href="products.php?action=delete&product_id=<?= $product['product_id'] ?>" class="delete">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>