<?php
$host = 'localhost';
$dbname = 'Duchex';
$username = 'root';
$password = '';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
    exit();
}
?>
