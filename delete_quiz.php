<?php
require 'dbconnection.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(["status" => "error", "message" => "Missing quiz ID"]);
    exit;
}

$id = intval($data['id']);

// Delete related questions first
$conn->query("DELETE FROM questions WHERE quiz_id = $id");
// Then delete the quiz
$conn->query("DELETE FROM quizzes WHERE id = $id");

if ($conn->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Quiz deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Quiz not found or already deleted"]);
}

$conn->close();
?>
