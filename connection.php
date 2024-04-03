<?php
$dns    = "localhost";
$user   = "root";
$pass   = "";

try {
    $conn = new PDO("mysql:host=$dns;dbname=api", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
