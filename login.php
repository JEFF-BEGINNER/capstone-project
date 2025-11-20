<?php 
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "school_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = trim($_POST['student_number']); // student_number / teacher_number OR email
    $password = $_POST['password'];
    $accountType = $_POST['account_type'] ?? '';

    if ($accountType === "Teacher") {
        // TEACHER LOGIN
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT teacher_id, first_name, last_name, password 
                    FROM teacher_tbl 
                    WHERE TRIM(email) = ?";
        } else {
            $sql = "SELECT teacher_id, first_name, last_name, password 
                    FROM teacher_tbl 
                    WHERE TRIM(teacher_number) = ?";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $teacher = $result->fetch_assoc();
            if (password_verify($password, $teacher['password'])) {
                $_SESSION['teacher_id'] = $teacher['teacher_id'];
                $_SESSION['teacher_name'] = $teacher['first_name'] . " " . $teacher['last_name'];
                header("Location: TeacherDashboard.php");
                exit();
            } else {
                echo "<script>alert('❌ Incorrect password for teacher account'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('❌ Teacher account not found.'); window.history.back();</script>";
            exit();
        }
    } 
    
    else if ($accountType === "Student") {

        // STUDENT LOGIN: email or student number
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $sql = "SELECT student_id, first_name, last_name, password, class_id 
                    FROM student_tbl 
                    WHERE TRIM(email) = ?";
        } else {
            $sql = "SELECT student_id, first_name, last_name, password, class_id
                    FROM student_tbl 
                    WHERE TRIM(student_number) = ?";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $student = $result->fetch_assoc();

            if (password_verify($password, $student['password'])) {

                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['student_name'] = $student['first_name'] . " " . $student['last_name'];

                // ⭐⭐⭐⭐⭐ AUTO ASSIGN CLASS IF NULL ⭐⭐⭐⭐⭐
                if ($student['class_id'] === NULL) {

                    // CHANGE THIS — your default section
                    $default_class_id = 1;   // Sapphire

                    $assign = $conn->prepare("
                        UPDATE student_tbl 
                        SET class_id = ? 
                        WHERE student_id = ?
                    ");
                    $assign->bind_param("ii", $default_class_id, $student['student_id']);
                    $assign->execute();
                }
                // ⭐⭐⭐⭐⭐ END AUTO ASSIGN ⭐⭐⭐⭐⭐

                header("Location: StudentDashboard.php");
                exit();

            } else {
                echo "<script>alert('❌ Incorrect password for student account'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('❌ Student account not found. Please check your login details.'); window.history.back();</script>";
            exit();
        }
    } 
    
    else {
        echo "<script>alert('❌ Please select a valid account type.'); window.history.back();</script>";
        exit();
    }
}
?>
