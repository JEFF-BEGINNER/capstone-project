<?php 
// Connect to the database
$host = "localhost";
$dbname = "school_db";
$username = "root";
$password = ""; // change if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST['first_name'] ?? '');
    $middleInitial = trim($_POST['middle_initial'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $studentNumber = trim($_POST['student_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $section = trim($_POST['section'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Password validation
    if ($password !== $confirmPassword) {
        $error = "❌ Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "❌ Password must be at least 8 characters.";
    } else {
        // 🔍 Check if student number already exists
        $checkStudentNum = $pdo->prepare("SELECT student_id FROM student_tbl WHERE student_number = ?");
        $checkStudentNum->execute([$studentNumber]);

        if ($checkStudentNum->rowCount() > 0) {
            $error = "❌ Student Number already exists.";
        } else {
            // 🔍 Check if email already exists
            $checkEmail = $pdo->prepare("SELECT student_id FROM student_tbl WHERE email = ?");
            $checkEmail->execute([$email]);

            if ($checkEmail->rowCount() > 0) {
                $error = "❌ Email already registered.";
            } else {
                // ✅ Insert student if no duplicates
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO student_tbl 
                    (student_number, first_name, middle_initial, last_name, email, password, section) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");

                $stmt->execute([
                    $studentNumber,
                    $firstName,
                    $middleInitial,
                    $lastName,
                    $email,
                    $hashedPassword,
                    $section
                ]);

                // Redirect after success
                header("Location: accType.html");
                exit();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create an Account</title>
 <link rel="stylesheet" href="signupstudent1.css" />
  
</head>
<body>
  <div class="overlay">
    <div class="form-container">
      <a href="accType.html" class="close-btn">X</a>

      <h2>Create an account</h2>

      <!-- Show Error Message -->
      <?php if (!empty($error)): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <!-- Signup Form -->
      <form method="post" action="signupStudent.php" novalidate>
        <div class="form-row">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input id="first_name" type="text" name="first_name" required />
          </div>
          <div class="form-group">
            <label for="student_number">Student Number (LRN)</label>
            <input id="student_number" type="text" name="student_number" required pattern="320701[0-9]{6}" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="middle_initial">Middle Initial</label>
            <input id="middle_initial" type="text" name="middle_initial" maxlength="1" />
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input id="last_name" type="text" name="last_name" required />
          </div>
          <div class="form-group">
            <label for="password">Create Password</label>
            <input id="password" type="password" name="password" required pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}" />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="section">Section</label>
            <select id="section" name="section" required>
              <option value="">-- Select Section --</option>
              <option value="Diamond">Diamond</option>
              <option value="Agate">Agate</option>
              <option value="Amber">Amber</option>
              <option value="Amethyst">Amethyst</option>
              <option value="Aquamarine">Aquamarine</option>
              <option value="Citrine">Citrine</option>
              <option value="Emerald">Emerald</option>
              <option value="Jade">Jade</option>
              <option value="Jasper">Jasper</option>
              <option value="Opal">Opal</option>
              <option value="Peridot">Peridot</option>
              <option value="Quartz">Quartz</option>
              <option value="Ruby">Ruby</option>
              <option value="Sapphire">Sapphire</option>
            </select>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input id="confirm_password" type="password" name="confirm_password" required />
          </div>
        </div>

        <div class="checkbox login-link">
          <input type="checkbox" id="terms" required />
          <label for="terms">
            <strong>By signing up, you agree to our</strong>
            <a href="#" onclick="openTerms(event)">Terms & Conditions</a>
            <strong> and Privacy Policy</strong>
          </label>
        </div>

        <button class="signup-btn" type="submit">Sign up</button>

        <p class="login-link">
          <strong>Already have an account?</strong> <a href="accType.html">Log In</a>
        </p>
      </form>
    </div>
  </div>

  <!-- Terms Modal -->
  <div id="termsModal" class="modal hidden" role="dialog" aria-modal="true" aria-labelledby="termsTitle" aria-describedby="termsDesc">
    <div class="modal-content">
      <span class="close-modal" onclick="closeTerms()" role="button" aria-label="Close">&times;</span>
      <h1 id="termsTitle">Terms and Conditions</h1>
      <div id="termsDesc">
        <!-- Add your terms and privacy content here -->
        <p>Put your terms and privacy policy text here...</p>
      </div>
    </div>
  </div>

  <script>
    function openTerms(e) {
      e.preventDefault();
      document.getElementById("termsModal").style.display = "flex";
    }
    function closeTerms() {
      document.getElementById("termsModal").style.display = "none";
    }
    window.onclick = function(event) {
      const modal = document.getElementById("termsModal");
      if (event.target === modal) {
        modal.style.display = "none";
      }
    };
  </script>
</body>
</html>
