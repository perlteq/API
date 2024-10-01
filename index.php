<?php
header('Content-Type: application/json');

// Include your database connection
require 'db_connection.php';

// Read the JSON request body
$data = json_decode(file_get_contents('php://input'), true);

// Check if action is specified (Signup or login)
if (isset($data['action'])) {
    $action = $data['action'];

    if ($action === 'Signup') {
        // Registration logic
        if (isset($data['email']) && isset($data['password'])) {
            $email = $data['email'];
            $password = $data['password'];

            // Check if the email is already Signuped
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email is already Signuped'
                ]);
            } else {
                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the database
                $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
                if ($stmt->execute(['email' => $email, 'password' => $hashed_password])) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Registration successful'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Registration failed, please try again'
                    ]);
                }
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email and password are required'
            ]);
        }
    } elseif ($action === 'login') {
        // Login logic
        if (isset($data['email']) && isset($data['password'])) {
            $email = $data['email'];
            $password = $data['password'];

            // Prepare SQL statement to fetch the user by email
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if ($user && password_verify($password, $user['password'])) {
                // Generate a JWT token (optional, see below)
                $secret_key = 'your_secret_key';
                $issuer = 'yourdomain.com';
                $issued_at = time();
                $expiration_time = $issued_at + (60 * 60); // Token valid for 1 hour
                $payload = array(
                    "iss" => $issuer,
                    "iat" => $issued_at,
                    "exp" => $expiration_time,
                    "data" => array(
                        "id" => $user['id'],
                        "email" => $user['email']
                    )
                );

                $jwt = JWT::encode($payload, $secret_key);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $jwt // Include token if using JWT
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid email or password'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email and password are required'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid action specified'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Action is required'
    ]);
}
?>
