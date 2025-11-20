<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "dbconnection.php";

// Check login
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher info
$stmt = $conn->prepare("SELECT first_name, last_name, email, teacher_number, avatar 
                        FROM teacher_tbl WHERE teacher_id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

// Data preparation
if ($teacher) {
    $first = $teacher['first_name'];
    $last  = $teacher['last_name'];
    if (!empty($last) && stripos($first, $last) !== false) {
        $name = $first;
    } else {
        $name = trim($first . " " . $last);
    }

    $email  = $teacher['email'] ?? "No Email";
    $tnum   = $teacher['teacher_number'] ?? "N/A";
    $avatar = $teacher['avatar'] ?? "image/teacher1.jpg";
} else {
    $name   = "Teacher Name";
    $email  = "No Email";
    $tnum   = "N/A";
    $avatar = "image/teacher1.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Teacher Dashboard</title>
<link rel="stylesheet" href="TeacherDashboard2.css" />
<link rel="stylesheet" href="TeacherAbout.css" />
<link rel="stylesheet" href="TeacherProfile.css" />
<link rel="stylesheet" href="TeacherHome.css" />
<link rel="stylesheet" href="TeacherClasses.css" />
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
      <p><strong id="teacherName"><?php echo htmlspecialchars($name); ?></strong><br>Teacher</p>
      <button class="profile-btn" data-page="profile">👤 View Profile</button>
    </div>
    <nav class="nav-links">
      <a href="#" data-page="home">🏠 Home</a>
      <a href="#" data-page="classes">🏫 Classes</a>
      <a href="TeacherCreate.html">➕ Create Quiz</a>
      <a href="#" data-page="classReport">📊 Class Report</a>
      <a href="#" data-page="quizzes">📂 Quizzes</a>
      <a href="#" data-page="about">ℹ️ About</a>
    </nav>
  </aside>

  <!-- Top Bar Template -->
  <template id="topBarTemplate">
    <header class="top-bar">
      <div class="icons left-icons">
        <button class="notification-btn">🔔</button>
        <button id="darkModeBtn">🌙</button>
        <button class="settings-btn">⚙️</button>
        <div id="settingsMenu" class="settings hidden">
          <button id="logoutBtn">Log out</button>
        </div>
      </div>
    </header>
  </template>

  <!-- Class Modal -->
  <div id="classModal" class="modal hidden">
    <div class="modal-content">
      <span class="close-modal" onclick="closeClassInput()">×</span>
      <h2>Enter Class Name</h2>
      <div class="input-wrapper">
        <input type="text" placeholder="Enter class..." id="classInput" />
        <button id="submitClass">➤</button>
      </div>
    </div>
  </div>

  <!-- Home Content -->
  <main class="main-content" id="mainHomeContent">
    <div class="top-bar-container"></div>
    <section class="quick-access">
      <div class="card yellow" onclick="window.location.href='TeacherCreate.html'">
        <div class="card-icon">➕</div>
        <div class="card-title">Create Quiz</div>
      </div>
      <div class="card blue" onclick="showQuizzes()">
        <div class="card-icon">📂</div>
        <div class="card-title">Quizzes</div>
      </div>
      <div class="card red" onclick="showClasses()">
        <div class="card-icon">🏫</div>
        <div class="card-title">Classes</div>
      </div>
      <div class="card yellow" onclick="showClassReport()">
        <div class="card-icon">📊</div>
        <div class="card-title">Class Report</div>
      </div>
    </section>
    <p class="welcome-text">Welcome, Teacher! This is your home dashboard.</p>
    <div class="wave-deco"></div>

    <section class="class-report-summary">
      <h2>📊 Class Report Summary</h2>
      <div class="report-table-container">
        <table border="1" class="report-table">
          <thead>
            <tr>
              <th>Class Name</th>
              <th>Students</th>
              <th>Avg Score</th>
              <th>Total Quizzes</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $sql = "
            SELECT 
                c.class_id,
                c.class_name,
                COUNT(DISTINCT s.student_id) AS student_count,
                ROUND(AVG(qr.score), 2) AS avg_score,
                COUNT(DISTINCT q.quiz_id) AS total_quizzes
            FROM class_tbl c
            LEFT JOIN student_tbl s ON s.class_id = c.class_id
            LEFT JOIN quiz_tbl q ON q.class_id = c.class_id
            LEFT JOIN quiz_result_tbl qr ON qr.quiz_id = q.quiz_id
            WHERE c.teacher_id = ?
            GROUP BY c.class_id, c.class_name
            ORDER BY c.class_name ASC
          ";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $teacher_id);
          $stmt->execute();
          $result = $stmt->get_result();
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>" . htmlspecialchars($row['class_name']) . "</td>
                      <td>" . htmlspecialchars($row['student_count']) . "</td>
                      <td>
                        <div class='score-cell'>
                          <div class='score-bar' style='width:" . ($row['avg_score'] ?? 0) . "%'></div>
                          <span>" . ($row['avg_score'] ?? 0) . "%</span>
                        </div>
                      </td>
                      <td>" . htmlspecialchars($row['total_quizzes']) . "</td>
                    </tr>";
            }
          } else {
            echo "<tr><td colspan='4'>No class data available yet.</td></tr>";
          }
          $stmt->close();
          ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- Classes -->
  <div id="classContent" class="main-content hidden">
  <div class="top-bar-container"></div>

  <div class="classes-panel">
    <!-- Dropdown -->
    <div class="mini-class-dropdown">
      <div class="dropdown-toggle" onclick="toggleDropdown()">Select Section ▼</div>
      <ul class="dropdown-list" id="sectionDropdownList">
        <li class="section-item" onclick="loadSectionStudents('Diamond')">Diamond</li>
        <li class="section-item" onclick="loadSectionStudents('Agate')">Agate</li>
        <li class="section-item" onclick="loadSectionStudents('Amber')">Amber</li>
        <li class="section-item" onclick="loadSectionStudents('Amethyst')">Amethyst</li>
        <li class="section-item" onclick="loadSectionStudents('Aquamarine')">Aquamarine</li>
        <li class="section-item" onclick="loadSectionStudents('Citrine')">Citrine</li>
        <li class="section-item" onclick="loadSectionStudents('Emerald')">Emerald</li>
        <li class="section-item" onclick="loadSectionStudents('Jade')">Jade</li>
        <li class="section-item" onclick="loadSectionStudents('Jasper')">Jasper</li>
        <li class="section-item" onclick="loadSectionStudents('Opal')">Opal</li>
        <li class="section-item" onclick="loadSectionStudents('Peridot')">Peridot</li>
        <li class="section-item" onclick="loadSectionStudents('Quartz')">Quartz</li>
        <li class="section-item" onclick="loadSectionStudents('Ruby')">Ruby</li>
        <li class="section-item" onclick="loadSectionStudents('Sapphire')">Sapphire</li>
      </ul>
    </div>

    <!-- Students -->
    <div id="studentsContainer" class="students-container">
      <p>Select a section to view students</p>
    </div>
  </div>
</div>

    

  <!-- Create Quiz -->
  <div id="createQuizContent" class="main-content hidden">
    <div class="top-bar-container"></div>
    <div class="section-body">
      <h2>➕ Create Quiz</h2>
      <p>Use this section to create quizzes for your classes.</p>
    </div>
  </div>

  <!-- Class Report -->
  <div id="classReportContent" class="main-content hidden">
    <div class="top-bar-container"></div>
    <div class="section-body">
      <h2>📊 Class Report</h2>
      <p>Monitor class performance and student analytics here.</p>
    </div>
  </div>

  <!-- Quizzes -->
  <div id="quizzesContent" class="main-content hidden">
    <div class="top-bar-container"></div>
    <div class="section-body">
      <h2>📂 All Quizzes</h2>
      <p>View, edit, or delete quizzes you’ve created.</p>
    </div>
  </div>

  <!-- Profile -->
  <div id="profileContent" class="main-content hidden">
    <header class="top-bar"><h2 style="margin-left:20px;">Profile</h2></header>
    <div class="profile-edit-wrapper">
      <div class="profile-avatar-section">
        <div class="large-avatar-preview">
          <img id="bigAvatarPreview" src="<?php echo htmlspecialchars($avatar); ?>" alt="Preview" />
        </div>
        <div class="avatar-carousel avatar-selection">
          <img src="image/teacher1 (1).jpg" onclick="selectAvatar(this)" alt="Teacher 1">
          <img src="image/teacher1 (2).jpg" onclick="selectAvatar(this)" alt="Teacher 2">
          <img src="image/teacher1 (3).jpg" onclick="selectAvatar(this)" alt="Teacher 3">
          <img src="image/teacher1 (4).jpg" onclick="selectAvatar(this)" alt="Teacher 4">
          <img src="image/teacher1 (5).jpg" onclick="selectAvatar(this)" alt="Teacher 5">
        </div>
      </div>
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
          <input type="text" id="sidebarNumber" value="<?php echo htmlspecialchars($tnum); ?>" />
          <span class="edit-icon">✏️</span>
        </div>
        <div class="save-section">
          <button class="save-button" onclick="saveProfile()">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- About -->
  <div id="aboutModal" class="modal hidden">
    <div class="modal-content">
      <span class="close-modal" onclick="closeAbout()">×</span>
      <h1>About</h1>
      <p>AI-POWERED ADAPTIVE QUIZ SYSTEM WITH REAL-TIME PERFORMANCE ANALYTICS FOR GRADE 10 STUDENTS.</p>
      <section>
        <ul>
          <li>AI-generated quizzes tailored to each student's level</li>
          <li>Real-time performance analytics</li>
          <li>Curriculum-based Grade 10 content</li>
          <li>Teacher monitoring tools</li>
          <li>Personalized feedback</li>
          <li>Secure data management</li>
          <li>Optimized for desktop & mobile</li>
        </ul>
      </section>
    </div>
  </div>
</div>

<script>
let selectedAvatar = "<?php echo htmlspecialchars($avatar); ?>";

function hideAllMain() {
  document.querySelectorAll(".main-content").forEach(el => el.classList.add("hidden"));
}

function showSection(id) {
  hideAllMain();
  if(id !== "profileContent") injectTopBar(id);
  document.getElementById(id).classList.remove("hidden");
}

function showHome() { showSection("mainHomeContent"); }
function showClasses() { showSection("classContent"); }
function showCreateQuiz() { showSection("createQuizContent"); }
function showClassReport() { showSection("classReportContent"); }
function showQuizzes() { showSection("quizzesContent"); }
function showProfile() { showSection("profileContent"); }
function showAbout() { const modal = document.getElementById("aboutModal"); modal.classList.remove("hidden"); modal.style.display = "flex"; }
function closeAbout() { const modal = document.getElementById("aboutModal"); modal.classList.add("hidden"); modal.style.display = "none"; }

function injectTopBar(sectionId) {
  const section = document.getElementById(sectionId);
  const topBarContainer = section.querySelector(".top-bar-container");
  if(!topBarContainer) return;
  topBarContainer.innerHTML = "";
  const template = document.getElementById("topBarTemplate");
  topBarContainer.appendChild(template.content.cloneNode(true));

  const darkModeBtn = topBarContainer.querySelector("#darkModeBtn");
  darkModeBtn?.addEventListener("click", toggleDarkMode);

  const settingsBtn = topBarContainer.querySelector(".settings-btn");
  const settingsMenu = topBarContainer.querySelector(".settings");
  settingsBtn?.addEventListener("click", () => settingsMenu?.classList.toggle("hidden"));

  const logoutBtn = topBarContainer.querySelector("#logoutBtn");
  logoutBtn?.addEventListener("click", logout);
}

function toggleDarkMode() { document.body.classList.toggle("dark-mode"); }
function logout() { if(confirm("Are you sure you want to logout?")) window.location.href="logout.php"; }

function selectAvatar(img) {
  selectedAvatar = img.src;
  document.querySelectorAll(".avatar-selection img").forEach(el => el.classList.remove("selected"));
  img.classList.add("selected");
  document.getElementById("bigAvatarPreview").src = selectedAvatar;
}

function saveProfile() {
  const name = document.getElementById('sidebarName').value;
  const email = document.getElementById('sidebarEmail').value;
  const num = document.getElementById('sidebarNumber').value;
  if(!name.trim()){ alert("Name cannot be empty"); return; }

  fetch('updateTeacherProfile.php', {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({name,email,teacher_number:num,avatar:selectedAvatar})
  }).then(res=>res.json())
    .then(data=>{
      if(data.status==="success"){
        alert(data.message);
        document.getElementById('teacherName').textContent = name;
        document.getElementById('profileImage').src = selectedAvatar;
        document.getElementById('bigAvatarPreview').src = selectedAvatar;
      } else alert("Error: "+data.message);
    }).catch(err=>alert("An error occurred: "+err));
}

// Sidebar nav
document.querySelectorAll(".nav-links a, .profile-btn").forEach(link=>{
  const page = link.dataset.page;
  if(page){
    link.addEventListener("click", e=>{
      e.preventDefault();
      switch(page){
        case "home": showHome(); break;
        case "classes": showClasses(); break;
        case "classReport": showClassReport(); break;
        case "quizzes": showQuizzes(); break;
        case "profile": showProfile(); break;
        case "about": showAbout(); break;
      }
    });
  }
});

// Initial load
document.addEventListener("DOMContentLoaded", ()=>{ showHome(); });

// Class dropdown
function toggleDropdown() {
  document.getElementById('sectionDropdownList').classList.toggle('show');
}
function loadSectionStudents(section) {
  const list = document.getElementById('sectionDropdownList');
  const container = document.getElementById('studentsContainer');
  list.classList.remove('show');
  document.querySelectorAll('.section-item').forEach(item=>item.classList.toggle('active', item.textContent===section));
  container.innerHTML = `<p>Loading students for <strong>${section}</strong>...</p>`;
  fetch(`getSectionStudents.php?section=${encodeURIComponent(section)}`)
    .then(res=>res.json())
    .then(students=>{
      if(students.length===0){ container.innerHTML = `<p>No students in <strong>${section}</strong>.</p>`; return; }
      let html = `<h3>Section: ${section}</h3><ul class="student-list">`;
      students.forEach(s=>html+=`<li>${s.name}</li>`);
      html+=`</ul>`;
      container.innerHTML = html;
    })
    .catch(err=>container.innerHTML=`<p>Error loading students: ${err}</p>`);
}
document.addEventListener('click', e=>{
  const dropdown = document.querySelector('.mini-class-dropdown');
  if(!dropdown.contains(e.target)) document.getElementById('sectionDropdownList').classList.remove('show');
});

// Class modal
const submitClass = document.getElementById("submitClass");
const classInput = document.getElementById("classInput");
function handleClassSubmit(){
  const classname = classInput.value.trim();
  if(classname){ showClasses(); classInput.value=""; document.getElementById("classModal").classList.add("hidden"); }
  else alert("⚠️ Please enter a class name!");
}
submitClass?.addEventListener("click", handleClassSubmit);
classInput?.addEventListener("keypress", e=>{ if(e.key==="Enter"){ e.preventDefault(); handleClassSubmit(); } });
function closeClassInput(){ document.getElementById("classModal").classList.add("hidden"); }

</script>
</body>
</html>
