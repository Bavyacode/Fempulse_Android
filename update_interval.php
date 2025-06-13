<?php
include "includes/dbconnection.php";
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

// Check if interval_days is provided
if (isset($data['interval_days'])) {
    $interval_days = intval($data['interval_days']);

    // Update latest cycle_data row for this user
    $stmt = $conn->prepare("
        UPDATE cycle_data 
        SET interval_days = ? 
        WHERE user_id = ? 
        ORDER BY start_date DESC 
        LIMIT 1
    ");
    $stmt->bind_param("ii", $interval_days, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Cycle interval updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing interval_days"]);
}

$conn->close();
?>
