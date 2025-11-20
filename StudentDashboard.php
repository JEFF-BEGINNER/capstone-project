<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "dbconnection.php";

// Check login
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student info
$stmt = $conn->prepare("SELECT first_name, last_name, email, student_number, avatar, section  
                        FROM student_tbl WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Data preparation
if ($student) {
    $first = $student['first_name'];
    $last  = $student['last_name'];
    if (!empty($last) && stripos($first, $last) !== false) {
        $name = $first;
    } else {
        $name = trim($first . " " . $last);
    }

    $email  = $student['email'] ?? "No Email";
    $lrn    = $student['student_number'] ?? "N/A";
    $avatar = $student['avatar'] ?? "image/boy1.jpg";
    $section = htmlspecialchars($student['section']); // ← ito yung section

} else {
    $name   = "Student Name";
    $email  = "No Email";
    $lrn    = "N/A";
    $avatar = "image/boy1.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="studentDashboard.css" />
  <link rel="stylesheet" href="about.css" />
  <link rel="stylesheet" href="studentTodo1.css" />
  <link rel="stylesheet" href="StudentProfile.css" />
  <link rel="stylesheet" href="studentSubject.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="logo">QUIZ</div>
      <div class="profile">
        <div class="profile-image-container">
          <img id="profileImage" src="<?php echo htmlspecialchars($avatar); ?>" alt="Profile" />
        </div>
        <p><strong id="studentName"><?php echo htmlspecialchars($name); ?></strong><br>Student</p>
        <p><strong><?= $section ?></strong></p>
        <button class="profile-btn" onclick="showProfile()">👤 View Profile</button>
      </div>
      <nav class="nav-links">
        <a href="#" onclick="showHome()">🏠 Home</a>
        <a href="#" onclick="showSubject('Subject')">📖 Subjects</a>
        <a href="#" onclick="showTodo()">✅ To-Do</a>
        <a href="#" onclick="showAbout()">ℹ️ About</a>
      </nav>
    </aside>

    <!-- CLASS CODE MODAL -->
    <div id="classCodeModal" class="modal hidden">
      <div class="modal-content">
        <span class="close-modal" onclick="closeClassCodeModal()">×</span>
        <p id="modalMessage"></p>
      </div>
    </div>

    <!-- TEMPLATE TOP BAR -->
    <template id="topBarTemplate">
  <header class="top-bar">
    <div class="icons left-icons">
      <div class="plus-container">
        <button class="plus-btn">+</button>
        <div class="subject-box hidden" id="subjectBox">
          <div class="input-wrapper">
            <input type="text" placeholder="Enter subject..." id="subjectInput" />
            <button id="submitSubject">➤</button>
          </div>
        </div>
      </div>
      <button class="notification-btn">🔔</button>
      <button class="darkmode-btn">🌙</button>
      <button class="settings-btn">⚙️</button>
      <div id="settingsMenu" class="settings hidden">
        <button id="logoutBtn">Log out</button>
      </div>
    </div>
  </header>
</template>


    <!-- HOME CONTENT -->
    <main class="main-content" id="mainHomeContent">
      <div class="top-bar-container"></div>
      <section class="quick-access">
        <div class="card yellow" onclick="showSubject('Subject')">
          <div class="card-icon">📚</div>
          <div class="card-title">Subjects</div>
        </div>
        <div class="card red" onclick="showTodo()">
          <div class="card-icon">📝</div>
          <div class="card-title">To-do</div>
        </div>
        <div class="card blue">
          <div class="card-icon">📂</div>
          <div class="card-title">All Quizzes</div>
        </div>
      </section>
    </main>

    <!-- SUBJECT -->
<div id="subjectContent" class="main-content hidden">
  <div class="top-bar-container"></div>
  <div class="section-body">
    <h2>📖 My Subjects</h2>

    <!-- Placeholder kung walang subjects -->
<div id="subjectPlaceholder" class="subject-placeholder">
  <img src="image/empty-subject.png" alt="No Subjects Yet" />
  <h3>📚 No Subjects Added</h3>
  <p>You haven’t added any subjects yet. Click the <b>+</b> button above to get started!</p>
  <button class="add-subject-btn" onclick="showAddSubjectModal()">➕ Add Subject</button>
</div>


    <!-- Kapag may subjects na, dito sila papasok -->
    <div id="subjectsGrid" class="subjects-grid hidden"></div>
  </div>
</div>

<!-- ADD SUBJECT MODAL -->
<div id="subjectModal" class="modal hidden">
  <div class="modal-content">
    <span class="close-modal" onclick="closeSubjectModal()">×</span>
    <h3>Add New Subject</h3>
    <input type="text" id="newSubjectInput" placeholder="Enter subject name...">
    <button onclick="addSubject()">Add</button>
  </div>
</div>


    <!-- TODO -->
    <div id="todoContent" class="main-content hidden">
      <div class="top-bar-container"></div>
      <div class="section-body">
        <h2>📝 To-Do List</h2>
        <div class="todo-tabs">
          <button class="tab-btn active" onclick="showTodoTab('assigned')">Assigned</button>
          <button class="tab-btn" onclick="showTodoTab('missing')">Missing</button>
          <button class="tab-btn" onclick="showTodoTab('done')">Done</button>
        </div>

        <!-- ASSIGNED -->
        <div class="todo-panel active" id="tab-assigned">
          <div class="assigned-container">
            <!-- No Due Date -->
            <div class="assigned-item">
              <button class="assigned-btn" onclick="toggleDropdown('noDueDateDropdown')">No Due Date ▼</button>
              <div class="assigned-dropdown" id="noDueDateDropdown"><p>No quiz yet</p></div>
            </div>
            <!-- This Week -->
            <div class="assigned-item">
              <button class="assigned-btn" onclick="toggleDropdown('thisWeekDropdown')">This Week ▼</button>
              <div class="assigned-dropdown" id="thisWeekDropdown"><p>No quiz yet</p></div>
            </div>
            <!-- Next Week -->
            <div class="assigned-item">
              <button class="assigned-btn" onclick="toggleDropdown('nextWeekDropdown')">Next Week ▼</button>
              <div class="assigned-dropdown" id="nextWeekDropdown"><p>No quiz yet</p></div>
            </div>
            <!-- Later -->
            <div class="assigned-item">
              <button class="assigned-btn" onclick="toggleDropdown('laterDropdown')">Later ▼</button>
              <div class="assigned-dropdown" id="laterDropdown"><p>No quiz yet</p></div>
            </div>
          </div>
        </div>

        <!-- MISSING -->
        <div class="todo-panel" id="tab-missing"><p>No missing tasks.</p></div>
        <!-- DONE -->
        <div class="todo-panel" id="tab-done"><p>No completed tasks yet.</p></div>
      </div>
    </div> <!-- ✅ properly closed todoContent -->

    <!-- PROFILE -->
    <div id="profileContent" class="main-content hidden">
      <div class="top-bar-container"></div>
      <div class="profile-edit-wrapper">
        <!-- Avatar -->
        <div class="profile-avatar-section">
          <div class="large-avatar-preview">
            <img id="bigAvatarPreview" src="<?php echo htmlspecialchars($avatar); ?>" alt="Preview" />
          </div>
          <div class="avatar-carousel avatar-selection">
            <img src="image/boy1.jpg" onclick="selectAvatar(this)" alt="Boy 1">
            <img src="image/boy2.jpg" onclick="selectAvatar(this)" alt="Boy 2">
            <img src="image/boy3.jpg" onclick="selectAvatar(this)" alt="Boy 3">
            <img src="image/girl1.jpg" onclick="selectAvatar(this)" alt="Girl 1">
            <img src="image/girl2.jpg" onclick="selectAvatar(this)" alt="Girl 2">
            <img src="image/girl3.jpg" onclick="selectAvatar(this)" alt="Girl 3">
          </div>
        </div>
        <!-- Editable Fields -->
        <div class="profile-fields">
          <div class="editable-input">
            <input type="text" id="sidebarName" value="<?php echo htmlspecialchars($name); ?>" />
            <span class="edit-icon">✏️</span>
          </div>
          <div class="editable-input">
            <input type="email" id="sidebarEmail" value="<?php echo htmlspecialchars($email); ?>" />
            <span class="edit-icon">✏️</span>
          </div>
          <div class="editable-input">
            <input type="text" id="sidebarLRN" value="<?php echo htmlspecialchars($lrn); ?>" />
            <span class="edit-icon">✏️</span>
          </div>
          <div class="save-section">
            <button class="save-button" onclick="saveProfile()">Save Changes</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ABOUT MODAL -->
    <div id="aboutModal" class="modal hidden">
      <div class="modal-content">
        <span class="close-modal" onclick="closeAbout()">×</span>
        <h1>About</h1>
        <p>AI-POWERED ADAPTIVE QUIZ SYSTEM WITH REAL-TIME PERFORMANCE ANALYTICS FOR GRADE 10 STUDENTS.</p>
        <section>
          <p>This web application is designed to provide an intelligent and personalized learning experience through adaptive quizzes powered by artificial intelligence.</p>
          <p><strong>Key Features:</strong></p>
          <ul>
            <li>AI-generated quizzes tailored to each student's current level</li>
            <li>Real-time performance tracking and analytics dashboard</li>
            <li>Curriculum-based content for Grade 10 subjects</li>
            <li>Performance monitoring tools for teachers and administrators</li>
            <li>Supports personalized feedback based on individual strengths and weaknesses</li>
            <li>Secure student profiles and data management</li>
            <li>Optimized for mobile and desktop devices</li>
            <li>Accessible anytime, anywhere</li>
          </ul>
        </section>
      </div>
    </div>
  </div> <!-- dashboard-container end -->

<script>
let selectedAvatar = "<?php echo htmlspecialchars($avatar); ?>";

// Sidebar toggle
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('active');
}

// Dark Mode
function toggleDarkMode() {
  document.querySelectorAll(".main-content").forEach(main => {
    main.classList.toggle("dark-mode");
  });
}

// Logout
function logout() {
  if (confirm("Are you sure you want to logout?")) {
    window.location.href = "logout.php";
  }
}

// Inject top bar
// Inject top bar
function injectTopBar(sectionId) {
  const section = document.getElementById(sectionId);
  const topBarContainer = section.querySelector(".top-bar-container");
  const template = document.getElementById("topBarTemplate");

  topBarContainer.innerHTML = "";
  const clone = template.content.cloneNode(true);
  topBarContainer.appendChild(clone);

  const topBar = topBarContainer.querySelector(".top-bar");

  // Notifications
  topBar.querySelector(".notification-btn")?.addEventListener("click", () => {
    alert("No notifications yet!");
  });

  // Dark mode
  const darkModeBtn = topBar.querySelector(".darkmode-btn");
  darkModeBtn?.addEventListener("click", toggleDarkMode);

  // Settings + Logout
  const settingsBtn = topBar.querySelector(".settings-btn");
  const settingsMenu = topBar.querySelector(".settings");
  settingsBtn?.addEventListener("click", () => {
    settingsMenu?.classList.toggle("hidden");
  });

  const logoutBtn = topBar.querySelector("#logoutBtn");
  logoutBtn?.addEventListener("click", logout);

  // === SUBJECT SYSTEM ===
  const plusBtn = topBar.querySelector(".plus-btn");
  const subjectBox = topBar.querySelector("#subjectBox");
  plusBtn?.addEventListener("click", () => {
    subjectBox?.classList.toggle("hidden");
  });

  const subjectInput = topBar.querySelector("#subjectInput");
  const submitSubject = topBar.querySelector("#submitSubject");

  function handleSubjectSubmit() {
    const subject = subjectInput.value.trim();
    if (subject) {
      showSubject(subject); // switch to subjectContent
      subjectInput.value = "";
      subjectBox.classList.add("hidden");
    } else {
      alert("⚠️ Please enter a subject!");
    }
  }

  submitSubject?.addEventListener("click", handleSubjectSubmit);
  subjectInput?.addEventListener("keypress", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      handleSubjectSubmit();
    }
  });
}


// Hide all sections
function hideAllMain() {
  document.querySelectorAll(".main-content").forEach(el => el.classList.add("hidden"));
}

function showHome() {
  hideAllMain();
  injectTopBar("mainHomeContent");
  document.getElementById("mainHomeContent").classList.remove("hidden");
}

function showSubject() {
  hideAllMain();
  injectTopBar("subjectContent");
  document.getElementById("subjectContent").classList.remove("hidden");

  const grid = document.getElementById("subjectsGrid");
  const placeholder = document.getElementById("subjectPlaceholder");

  if (grid.children.length === 0) {
    // kung wala pang subject
    placeholder.classList.remove("hidden");
    grid.classList.add("hidden");
  } else {
    // kung meron nang subject
    placeholder.classList.add("hidden");
    grid.classList.remove("hidden");
  }
}



function showTodo() {
  hideAllMain();
  injectTopBar("todoContent");
  document.getElementById("todoContent").classList.remove("hidden");
  showTodoTab('assigned');
}

function showAbout() {
  const modal = document.getElementById("aboutModal");
  modal.classList.remove("hidden");
  modal.style.display = "flex";
}

function closeAbout() {
  const modal = document.getElementById("aboutModal");
  modal.classList.add("hidden");
  modal.style.display = "none";
}

// Profile
function showProfile() {
  hideAllMain();
  injectTopBar("profileContent");
  document.getElementById("profileContent").classList.remove("hidden");
}

function saveProfile() {
  const name = document.getElementById('sidebarName').value;
  const email = document.getElementById('sidebarEmail').value;
  const lrn = document.getElementById('sidebarLRN').value;

  if (!name.trim()) {
    alert("Name cannot be empty");
    return;
  }

  const payload = { name, email, lrn, avatar: selectedAvatar };

  fetch('updateProfile.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "success") {
      alert(data.message);
      document.getElementById('studentName').textContent = name;
      document.getElementById('profileImage').src = selectedAvatar;
      document.getElementById('bigAvatarPreview').src = selectedAvatar;
      localStorage.setItem("studentName", name);
      localStorage.setItem("studentAvatar", selectedAvatar);
    } else {
      alert("Error: " + data.message);
    }
  })
  .catch(err => {
    alert("An error occurred: " + err);
  });
}

function selectAvatar(img) {
  selectedAvatar = img.src;
  document.querySelectorAll(".avatar-selection img").forEach(el => el.classList.remove("selected"));
  img.classList.add("selected");
  document.getElementById("bigAvatarPreview").src = selectedAvatar;
}

// To-Do Tabs
function showTodoTab(tab) {
  document.querySelectorAll(".todo-panel").forEach(panel => panel.classList.remove("active"));
  document.querySelectorAll(".todo-tabs .tab-btn").forEach(btn => btn.classList.remove("active"));

  const panelId = "tab-" + tab;
  const panel = document.getElementById(panelId);
  if (panel) panel.classList.add("active");

  const btn = document.querySelector(`.todo-tabs .tab-btn[onclick="showTodoTab('${tab}')"]`);
  if (btn) btn.classList.add("active");
}

// Dropdown toggle
function toggleDropdown(id) {
  let dropdown = document.getElementById(id);
  dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

document.addEventListener("DOMContentLoaded", () => {
  showHome();
});

function showAddSubjectModal() {
  document.getElementById("subjectModal").classList.remove("hidden");
}

function closeSubjectModal() {
  document.getElementById("subjectModal").classList.add("hidden");
}

function addSubject() {
  const input = document.getElementById("newSubjectInput");
  const subjectName = input.value.trim();
  if (!subjectName) {
    alert("⚠️ Please enter a subject!");
    return;
  }

  // Hide placeholder
  document.getElementById("subjectPlaceholder").classList.add("hidden");
  document.getElementById("subjectsGrid").classList.remove("hidden");

  // Add subject card
  const grid = document.getElementById("subjectsGrid");
  const card = document.createElement("div");
  card.classList.add("subject-card");
  card.innerHTML = `📘 ${subjectName}`;   // ← may icon na
  grid.appendChild(card);

  input.value = "";
  closeSubjectModal();
}


</script>
</body>
</html>
