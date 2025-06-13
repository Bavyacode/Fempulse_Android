<?php
include "includes/dbconnection.php";
session_start();

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !is_array($data)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// --- Handle updates for `users` table ---
$user_fields = [];
$user_params = [];
$user_types = "";

if (isset($data['username'])) {
    $user_fields[] = "username = ?";
    $user_params[] = trim($data['username']);
    $user_types .= "s";
}
if (isset($data['age'])) {
    $user_fields[] = "age = ?";
    $user_params[] = intval($data['age']);
    $user_types .= "i";
}
if (isset($data['email'])) {
    $user_fields[] = "email = ?";
    $user_params[] = trim($data['email']);
    $user_types .= "s";
}
if (!empty($data['password'])) {
    $user_fields[] = "password = ?";
    $user_params[] = password_hash($data['password'], PASSWORD_DEFAULT);
    $user_types .= "s";
}

$success_user = true;
if (!empty($user_fields)) {
    $sql = "UPDATE users SET " . implode(", ", $user_fields) . " WHERE id = ?";
    $user_params[] = $user_id;
    $user_types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($user_types, ...$user_params);
    $success_user = $stmt->execute();
    $stmt->close();
}

// --- Handle updates for `cycle_data` table ---
$cycle_fields = [];
$cycle_params = [];
$cycle_types = "";

if (isset($data['cycle_duration'])) {
    $cycle_fields[] = "duration = ?";
    $cycle_params[] = intval($data['cycle_duration']);
    $cycle_types .= "i";
}
if (isset($data['cycle_length'])) {
    $cycle_fields[] = "interval_days = ?";
    $cycle_params[] = intval($data['cycle_length']);
    $cycle_types .= "i";
}

$success_cycle = true;
if (!empty($cycle_fields)) {
    $sql2 = "UPDATE cycle_data SET " . implode(", ", $cycle_fields) . " WHERE user_id = ?";
    $cycle_params[] = $user_id;
    $cycle_types .= "i";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param($cycle_types, ...$cycle_params);
    $success_cycle = $stmt2->execute();
    $stmt2->close();
}

if ($success_user && $success_cycle) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to update profile"]);
}

$conn->close();
?>
