<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Auth</title>
</head>
<body>
    <?php
    $_SESSION['user_id'] = 1;
    $_SESSION['role'] = 'admin';

    var_dump($_SESSION);
    ?>
</body>
</html>