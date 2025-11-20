<?php
// Database connection
$host = "localhost";
$user = "root";       // palitan kung iba ang MySQL user
$pass = "";           // palitan kung may password
$db   = "school_db";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = trim($_POST['student_number']);
    $first_name     = trim($_POST['first_name']);
    $middle_initial = trim($_POST['middle_initial']);
    $last_name      = trim($_POST['last_name']);
    $email          = trim($_POST['email']);
    $password       = $_POST['password'];
    $confirm_pass   = $_POST['confirm_password'];
    $Section   = trim($_POST['Section']);

    // ✅ Password match check
    if ($password !== $confirm_pass) {
        die("❌ Passwords do not match!");
    }

    // ✅ Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Insert query
    $stmt = $conn->prepare("INSERT INTO student_tbl 
        (student_number, first_name, middle_initial, last_name, email, password, Section) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $student_number, $first_name, $middle_initial, $last_name, $email, $hashed_password, $Section);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Account created successfully!'); window.location.href='LogIn.html';</script>";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
