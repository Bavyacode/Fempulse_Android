<?php
include "includes/dbconnection.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id']; // Secure: use session user_id, not input

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['start_date'])) {
    $start_date = $data['start_date'];
    $duration = isset($data['duration']) ? intval($data['duration']) : null;
    $interval_days = isset($data['interval_days']) ? intval($data['interval_days']) : null;

    $stmt = $conn->prepare("INSERT INTO cycle_data (user_id, start_date, duration, interval_days) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $user_id, $start_date, $duration, $interval_days);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Cycle data saved"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}

$conn->close();
?>
