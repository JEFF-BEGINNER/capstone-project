<?php
session_start();
include "dbconnection.php";

if (!isset($_SESSION['student_id'])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit();
}

$student_id = $_SESSION['student_id'];
$data = json_decode(file_get_contents("php://input"), true);

$name   = trim($data['name'] ?? '');
$email  = trim($data['email'] ?? '');
$lrn    = trim($data['lrn'] ?? '');
$avatar = trim($data['avatar'] ?? '');

if (empty($name)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Name cannot be empty"]);
    exit();
}

$stmt = $conn->prepare("UPDATE student_tbl SET first_name = ?, email = ?, student_number = ?, avatar = ? WHERE student_id = ?");
$stmt->bind_param("ssssi", $name, $email, $lrn, $avatar, $student_id);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>
