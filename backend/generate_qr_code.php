<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../admin/phpqrcode/qrlib.php";

define("QR_FOLDER", __DIR__ . "/../assets/qr_codes/");
define("QR_WEB_PATH", "../assets/qr_codes/");

/* ---------------------------
   1. Handle Add Department
---------------------------- */
if (isset($_POST['add_department'])) {
    $deptName = trim($_POST['department_name'] ?? '');

    if (!empty($deptName)) {
        $stmt = $conn->prepare('INSERT INTO departments (Dname) VALUES (:Dname)');
        $stmt->execute([':Dname' => $deptName]);
        $_SESSION['qr-code'] = "Department added successfully!";
    } else {
        $_SESSION['qr-code'] = "Department name cannot be empty.";
    }
}

/* ---------------------------
   2. Handle Add Course
---------------------------- */
if (isset($_POST['add_course'])) {
    $courseName = trim($_POST['course_name'] ?? '');
    $courseCode = trim($_POST['course_code'] ?? '');
    $deptId = $_POST['department_id'] ?? null;
    $lecturer = trim($_POST['lecturer'] ?? '');

    if ($courseName && $courseCode && $deptId && $lecturer) {
        $stmt = $conn->prepare("INSERT INTO courses (cname, code, department_id, lecturer) 
                                VALUES (:cname, :code, :dept, :lecturer)");
        $stmt->execute([
            ':cname' => $courseName,
            ':code' => $courseCode,
            ':dept' => $deptId,
            ':lecturer' => $lecturer
        ]);
        $_SESSION['qr-code'] = "Course '$courseName' added successfully!";
    } else {
        $_SESSION['qr-code'] = "All fields are required to add a course.";
    }
}

/* ---------------------------
   3. Handle QR Generation
---------------------------- */
if (isset($_POST['generate_qr'])) {
    $courseId = $_POST['course_id'] ?? null;

    if (!$courseId) {
        $_SESSION['qr-code'] = "Please select a course before generating QR.";
    } else {
        $token = bin2hex(random_bytes(16));
        $expiry_time = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        if (!file_exists(QR_FOLDER)) {
            mkdir(QR_FOLDER, 0777, true);
        }
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

        $url = "{$scheme}://{$_SERVER['HTTP_HOST']}/backend/mark_attendance.php?token={$token}";
        $file = QR_FOLDER . "{$token}.png";
        QRcode::png($url, $file, QR_ECLEVEL_L, 6, 2);


$stmt = $conn->prepare("INSERT INTO sessions (session_code, expiry_time, course_id, qr_path) 
                        VALUES (:session_code, :expiry_time, :course_id, :qr_path)");
$stmt->execute([
    ':session_code' => $token,
    ':expiry_time' => $expiry_time,
    ':course_id' => $courseId,
    ':qr_path' => QR_WEB_PATH . $token . ".png"
]);

        $_SESSION['qr_image'] = QR_WEB_PATH . $token . ".png";
        $_SESSION['qr_image_time'] = time();
        $_SESSION['qr-code'] = "QR Code Generated Successfully!";
    }
}

/* ---------------------------
   4. Fetch Departments & Courses
---------------------------- */
$deptStmt = $conn->query("SELECT id, Dname FROM departments");
$departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);

// Filter courses by selected department
$selectedDept = $_POST['department_id'] ?? null;
$courses = [];
if ($selectedDept) {
    $courseStmt = $conn->prepare("SELECT id, cname FROM courses WHERE department_id = :dept");
    $courseStmt->execute([':dept' => $selectedDept]);
    $courses = $courseStmt->fetchAll(PDO::FETCH_ASSOC);
}

include __DIR__ . "/../partials/header.php";
?>

<div>Manage Courses &amp; Generate QR Code</div>

   <?php if (isset($_SESSION['qr-code'])): ?>
      <div class="error-message" id="message">
        <p><?=htmlspecialchars( $_SESSION['qr-code']);
        unset($_SESSION['qr-code']); ?></p>
        <i class="uil uil-times" id="error-cancel"></i>
      </div>
      <script>
        let errorCancel = document.getElementById('error-cancel');
        let message = document.getElementById('message');

        if (message) {
          message.classList.add("show");
          setTimeout(() => {
            message.classList.remove("show");
            message.classList.add("hide");
          }, 10000);
          errorCancel.addEventListener("click", () => {
            message.classList.remove('show');
            message.classList.add('hide');
          });
        }
      </script>
    <?php endif; ?>

<div class="qr-full-page">
  <form method="POST" class="qr-form departments">
    <h3>Add Department</h3>
    <label for="department_name">Department Name:</label>
    <input type="text" name="department_name" id="department_name" required>
    <button type="submit" name="add_department">Add Department</button>
  </form>
</div>

<form method="POST" class="qr-form courses">
  <h3>Add Course</h3>

  <label for="course_name">Course Name:</label>
  <input type="text" name="course_name" id="course_name" required>

  <label for="course_code">Course Code:</label>
  <input type="text" name="course_code" id="course_code" required>

  <label for="lecturer">Lecturer:</label>
  <input type="text" name="lecturer" id="lecturer" required>

  <label for="dept">Department:</label>
  <select name="department_id" id="dept" required>
    <option value="">-- Select Department --</option>
    <?php foreach ($departments as $department): ?>
      <option value="<?= $department['id'] ?>"><?= htmlspecialchars($department['Dname']) ?></option>
    <?php endforeach; ?>
  </select>

  <button type="submit" name="add_course">Add Course</button>
</form>

<!-- Generate QR -->
<form method="POST" class="qr-form">
  <h3>Generate QR Code</h3>

  <!-- Department dropdown -->
  <label for="department_id">Select Department:</label>
  <select name="department_id" id="department_id" required onchange="this.form.submit()">
    <option value="">-- Choose Department --</option>
    <?php foreach ($departments as $dept): ?>
      <option value="<?= $dept['id'] ?>" <?= ($selectedDept == $dept['id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($dept['Dname']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <!-- Course dropdown (filtered) -->
  <label for="course_id">Select Course:</label>
  <select name="course_id" id="course_id" required>
    <option value="">-- Choose Course --</option>
    <?php foreach ($courses as $course): ?>
      <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['cname']) ?></option>
    <?php endforeach; ?>
  </select>

  <span id="spinner" style="display:none;">⏳ Loading...</span>

  <button type="submit" name="generate_qr">Generate QR Code</button>
</form>

<!-- QR Display -->
<div class="qr-box">

  <?php 
  if (isset($_SESSION['qr_image_time']) && (time() - $_SESSION['qr_image_time'] > 1800)) {
      unset($_SESSION['qr_image'], $_SESSION['qr_image_time']);
  }
  ?>
  <?php if (isset($_SESSION['qr_image'])): ?>
    <img src="<?= htmlspecialchars($_SESSION['qr_image']) ?>" alt="QR Code!" class="qr-img">
  <?php endif; ?>
</div>

<script src="../assets/js/main.js"></script>
<script src="../assets/js/qr.js"></script>