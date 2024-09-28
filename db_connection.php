<?php
$host = 'localhost';
$dbname = 'your_database';
$username = 'your_db_user';
$password = 'your_db_password';

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
