<?php
include "includes/dbconnection.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['username']) &&
    isset($data['email']) &&
    isset($data['password']) &&
    isset($data['age'])
) {
    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // HASHING
    $age = intval($data['age']);

    // Check for existing user
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["status" => "error", "message" => "Email or username already exists"]);
        $check->close();
        exit();
    }
    $check->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, age) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $email, $password, $age);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "User registered successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
//close the dasebase
$conn->close();
?>
