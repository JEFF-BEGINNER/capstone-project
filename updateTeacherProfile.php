<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "dbconnection.php";

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit();
}

// Get teacher ID from session
$teacher_id = $_SESSION['teacher_id'];

// Get JSON data from fetch()
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!$data || !isset($data['name']) || !isset($data['email']) || !isset($data['teacher_number']) || !isset($data['avatar'])) {
    echo json_encode(["status" => "error", "message" => "Incomplete data"]);
    exit();
}

// Split the name into first and last if possible
$name_parts = explode(' ', trim($data['name']), 2);
$first_name = $name_parts[0];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

$email = $data['email'];
$teacher_number = $data['teacher_number'];
$avatar = $data['avatar'];

// Update the teacher profile in the database
$stmt = $conn->prepare("UPDATE teacher_tbl 
                        SET first_name = ?, last_name = ?, email = ?, teacher_number = ?, avatar = ? 
                        WHERE teacher_id = ?");
$stmt->bind_param("sssssi", $first_name, $last_name, $email, $teacher_number, $avatar, $teacher_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database update failed"]);
}
?>
