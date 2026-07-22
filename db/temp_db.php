<?php

$servername = "localhost";
$username ="root";
$password = "";
$dbname = "product_review_system";

try{
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    //PDO error handling
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected Successfully";
}
catch(PDOException $e){
    echo "Connection failed: " . $e ->getMessage();
}

?>