<?php
error_reporting(E_ALL);
header('Content-Type: application/json');

session_start();
include "dbconnection.php";

// Check if teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(['error' => 'Teacher not logged in']);
    exit;
}

$teacher_id = $_SESSION['teacher_id'];
$section = $_GET['section'] ?? '';
$students = [];

// Only fetch if section is provided
if (!empty($section)) {
    // Join student_tbl with class_tbl to verify teacher ownership
    $stmt = $conn->prepare("
        SELECT CONCAT(s.first_name, ' ', s.last_name) AS name
        FROM student_tbl s
        INNER JOIN class_tbl c ON s.class_id = c.class_id
        WHERE c.class_name = ? AND c.teacher_id = ?
        ORDER BY s.last_name ASC, s.first_name ASC
    ");
    $stmt->bind_param("si", $section, $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $students[] = ['name' => $row['name']];
    }

    $stmt->close();
}

// Return JSON always
echo json_encode($students);
exit;
