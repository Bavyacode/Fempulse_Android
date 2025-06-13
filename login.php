<?php
include "includes/dbconnection.php";

session_start(); // Start session at the beginning

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['username']) && isset($data['password'])) {
    $username = trim($data['username']);
    $password = $data['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userId, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;

            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user_id" => $userId,
                "username" => $username
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing username or password"]);
}

$conn->close();
?>
