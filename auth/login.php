<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

// If already logged in, send them straight to the product list
if (isset($_SESSION['user_id'])) {
    header("Location: ../products/list.php");
    exit;
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_msg = "Please enter both email and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT user_id, name, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            // Correct credentials - start the session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/moderate.php");
            } else {
                header("Location: ../products/list.php");
            }
            exit;
        } else {
            $error_msg = "Invalid email or password.";
        }
        mysqli_stmt_close($stmt);
    }
}

include '../includes/header.php';
?>

<h2>Log In</h2>

<?php if ($error_msg): ?>
    <div class="msg-error"><?= htmlspecialchars($error_msg) ?></div>
<?php endif; ?>

<form method="POST" action="login.php">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="you@example.com"
           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Your password" required>

    <input type="submit" value="Log In">
</form>

<p style="margin-top:20px;">Don't have an account? <a href="register.php">Register here</a></p>

<?php include '../includes/footer.php'; ?>
