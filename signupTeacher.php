<?php 
include("dbconnection.php");
$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $middle_initial = isset($_POST['middle_initial']) ? trim($_POST['middle_initial']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $teacher_number = isset($_POST['teacher_number']) ? trim($_POST['teacher_number']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_pass = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Check if Terms & Conditions agreed
    if (!isset($_POST['agree'])) {
        $message = "<div class='alert error'>❌ Please agree to Terms & Conditions.</div>";
    } elseif ($password !== $confirm_pass) {
        $message = "<div class='alert error'>❌ Passwords do not match.</div>";
    } elseif (strlen($password) < 8) {
        $message = "<div class='alert error'>❌ Password must be at least 8 characters.</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if teacher_number already exists
        $check_teacher_num = $conn->prepare("SELECT teacher_id FROM teacher_tbl WHERE teacher_number=?");
        $check_teacher_num->bind_param("s", $teacher_number);
        $check_teacher_num->execute();
        $result_teacher_num = $check_teacher_num->get_result();

        if ($result_teacher_num->num_rows > 0) {
            $message = "<div class='alert error'>❌ Teacher Number already exists.</div>";
        } else {
            // Check if email already exists
            $check_email = $conn->prepare("SELECT teacher_id FROM teacher_tbl WHERE email=?");
            $check_email->bind_param("s", $email);
            $check_email->execute();
            $result_email = $check_email->get_result();

            if ($result_email->num_rows > 0) {
                $message = "<div class='alert error'>❌ Email already registered.</div>";
            } else {
                // Insert new teacher record
                $sql = "INSERT INTO teacher_tbl (teacher_number, first_name, middle_initial, last_name, email, password) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $teacher_number, $first_name, $middle_initial, $last_name, $email, $hashed_password);

                if ($stmt->execute()) {
                    header("Location: accType.html");
                    exit();
                } else {
                    $message = "<div class='alert error'>❌ Error: " . $stmt->error . "</div>";
                }
                $stmt->close();
            }
            $check_email->close();
        }
        $check_teacher_num->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Create an account</title>
<link rel="stylesheet" href="signupteacher.css" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="wrap">
  <a class="close" href="accType.html">X</a>
  <h1>Create an account</h1>

  <?php if (!empty($message)) echo $message; ?>

  <form action="signupTeacher.php" method="POST" id="signupForm" novalidate>

    <div class="form-row">
      <div class="input-group">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="Enter first name" required>
      </div>
      <div class="input-group">
        <label for="teacher_number">Teacher Number (ID)</label>
        <input type="text" id="teacher_number" name="teacher_number" placeholder="Enter Teacher Number" required>
      </div>
    </div>

    <div class="form-row">
      <div class="input-group">
        <label for="middle_initial">Middle Initial</label>
        <input type="text" id="middle_initial" name="middle_initial" placeholder="Enter middle initial">
      </div>
      <div class="input-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter email address" required>
      </div>
    </div>

    <div class="form-row">
      <div class="input-group">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Enter last name" required>
      </div>
      <div class="input-group">
        <label for="password">Create Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>
      </div>
    </div>

    <div class="form-row">
      <div class="input-group full-width">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
      </div>
    </div>

    <div class="terms">
      <input type="checkbox" id="agree" name="agree" required>
      <label for="agree">
        By signing up, you agree to our 
        <a href="#">Terms & Conditions</a> and 
        <a href="#">Privacy Policy</a>
      </label>
    </div>

    <button type="submit" class="btn">Sign up</button>
  </form>

  <div class="or">Or</div>

  <div class="login">
    Already have an account? <a href="accType.html">Log In</a>
  </div>
</div>

<script>
  document.getElementById("signupForm").addEventListener("submit", function (e) {
    const pw = document.getElementById("password").value;
    const cpw = document.getElementById("confirm_password").value;
    if (pw !== cpw) {
      e.preventDefault();
      alert("Passwords do not match.");
    }
  });
</script>
</body>
</html>
