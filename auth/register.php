<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $error_msg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error_msg = "Passwords do not match.";
    } else {
        // Check if email already exists
        $check_stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error_msg = "An account with that email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // New accounts default to 'reviewer' role
            $insert_stmt = mysqli_prepare($conn,
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'reviewer')"
            );
            mysqli_stmt_bind_param($insert_stmt, "sss", $name, $email, $hashed_password);

            if (mysqli_stmt_execute($insert_stmt)) {
                $success_msg = "Account created successfully! You can now log in.";
            } else {
                $error_msg = "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($insert_stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}

include '../includes/header.php';
?>

<h2>Create an Account</h2>

<?php if ($success_msg): ?>
    <div class="msg-success"><?= htmlspecialchars($success_msg) ?>
        &mdash; <a href="login.php">Go to Login</a>
    </div>
<?php endif; ?>

<?php if ($error_msg): ?>
    <div class="msg-error"><?= htmlspecialchars($error_msg) ?></div>
<?php endif; ?>

<form method="POST" action="register.php">
    <label for="name">Full Name</label>
    <input type="text" name="name" id="name" placeholder="Your full name"
           value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="you@example.com"
           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="At least 6 characters" required>

    <label for="confirm_password">Confirm Password</label>
    <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>

    <input type="submit" value="Register">
</form>

<p style="margin-top:20px;">Already have an account? <a href="login.php">Log in here</a></p>

<?php include '../includes/footer.php'; ?>
