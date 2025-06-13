<?php
include "includes/dbconnection.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT u.username, u.age, u.email, c.duration AS cycle_duration, c.interval_days AS cycle_length
          FROM users u
          LEFT JOIN cycle_data c ON u.id = c.user_id
          WHERE u.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $profile = $result->fetch_assoc();
    echo json_encode(["status" => "success", "profile" => $profile]);
} else {
    echo json_encode(["status" => "error", "message" => "Profile not found"]);
}

$stmt->close();
$conn->close();
?>
