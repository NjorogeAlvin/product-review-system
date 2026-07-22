<?php
// Enable full PHP error reporting for quick debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host     = 'localhost';
$dbname   = 'product_review_system'; // Update if needed
$username = 'root';                  // Update if needed
$password = '';                      // Update if needed

try {
    // 1. Establish the connection using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // 2. Success Feedback
    echo "<h1>✅ Database Connection Successful!</h1>";
    echo "<p>Connected to <strong>{$dbname}</strong> on <strong>{$host}</strong>.</p>";

    // Optional: Test a quick query to verify table access
    $serverVersion = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    echo "<p>MySQL Server Version: <code>{$serverVersion}</code></p>";

} catch (PDOException $e) {
    // 3. Error Feedback
    echo "<h1>❌ Connection Failed</h1>";
    echo "<p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Error Code:</strong> " . htmlspecialchars((string)$e->getCode()) . "</p>";
}