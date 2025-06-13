<?php
include "includes/dbconnection.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

// Collect fields if present
$fields = [];
$params = [];
$types = "";

// Dynamically build query
if (isset($data['start_date'])) {
    $fields[] = "start_date = ?";
    $params[] = $data['start_date'];
    $types .= "s";
}

if (isset($data['duration'])) {
    $fields[] = "duration = ?";
    $params[] = intval($data['duration']);
    $types .= "i";
}

if (isset($data['interval_days'])) {
    $fields[] = "interval_days = ?";
    $params[] = intval($data['interval_days']);
    $types .= "i";
}

if (count($fields) === 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing fields to update"]);
    exit;
}

$sql = "UPDATE cycle_data SET " . implode(", ", $fields) . " WHERE user_id = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Cycle data updated"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
}

$stmt->close();
$conn->close();
?>
