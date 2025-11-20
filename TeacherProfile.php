<?php
// teacher_dashboard.php

// Start session if you have login system
session_start();

// Database connection
$host = 'localhost';
$db   = 'dbconnection.php';
$user = '';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Assuming teacher_number is stored in session after login
$teacher_number = $_SESSION['teacher_number'] ?? 1;

// Fetch teacher info
$stmt = $pdo->prepare("
    SELECT t.first_name, t.middle_initial, t.last_name, p.profile_image
    FROM teacher_tbl t
    LEFT JOIN teacher_profile p ON t.teacher_number = p.teacher_number
    WHERE t.teacher_number = ?
");
$stmt->execute([$teacher_number]);
$teacher = $stmt->fetch();

// Default if no profile image
$profile_image = $teacher['profile_image'] ?? 'teacher1 (1).jpg';
$teacher_name = $teacher['first_name'] . ' ' . $teacher['last_name'];
?>