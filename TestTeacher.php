<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "dbconnection.php";

// Temporary: set teacher_id manually for testing
$_SESSION['teacher_id'] = 1;

$teacher_id = $_SESSION['teacher_id'];

$stmt = $conn->prepare("SELECT first_name, last_name, email FROM teacher_tbl WHERE teacher_id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if ($teacher) {
    echo "<h1>Teacher Found:</h1>";
    echo "Name: " . htmlspecialchars($teacher['first_name']) . " " . htmlspecialchars($teacher['last_name']) . "<br>";
    echo "Email: " . htmlspecialchars($teacher['email']);
} else {
    echo "No teacher found with ID $teacher_id";
}
?>
