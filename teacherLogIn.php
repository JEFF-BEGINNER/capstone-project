<?php
session_start();
include("dbconnection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM teacher_tbl WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['teacher_id']   = $row['teacher_id'];
            $_SESSION['teacher_name'] = $row['first_name'] . " " . $row['last_name'];

            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
              Swal.fire({
                icon: 'success',
                title: 'Login Successful!',
                text: 'Welcome back, " . $row['first_name'] . "!',
                showConfirmButton: false,
                timer: 1500
              }).then(() => {
                window.location.href = 'TeacherDashboard.php';
              });
            </script>";
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Wrong password. Please try again.'
              }).then(() => {
                window.location.href = 'teacherLogin.html';
              });
            </script>";
        }
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
          Swal.fire({
            icon: 'warning',
            title: 'Not Found',
            text: 'No account found with that email.'
          }).then(() => {
            window.location.href = 'signupTeacher.html';
          });
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
