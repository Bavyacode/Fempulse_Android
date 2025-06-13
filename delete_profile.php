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

// Delete from cycle_data first (if it has a foreign key to users)
$sql1 = "DELETE FROM cycle_data WHERE user_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $user_id);
$success_cycle = $stmt1->execute();
$stmt1->close();

// Delete from users table
$sql2 = "DELETE FROM users WHERE id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$success_user = $stmt2->execute();
$stmt2->close();

if ($success_cycle && $success_user) {
    session_destroy();
    echo json_encode(["status" => "success", "message" => "Profile deleted successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to delete profile"]);
}

$conn->close();
?>
