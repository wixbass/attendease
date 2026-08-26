<?php
require_once __DIR__ . "/../config/database.php";

$fullname = $_SESSION['signup-data']['name'] ?? '';
$email = $_SESSION['signup-data']['email'] ?? '';
$pwd = $_SESSION['signup-data']['pwd'] ?? '';
$role = $_SESSION['signup-data']['role'] ?? '';

unset($_SESSION['signup-data']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup - Attendance</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/responsive.css">

  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.2.0/css/line.css">
</head>

<body>
  <div class="auth-body">
    <?php if (isset($_SESSION['signup'])): ?>
      <div class="error-message" id="message">
        <p><?= $_SESSION['signup'];
        unset($_SESSION['signup']); ?></p>
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

    <div class="form-container">
      <h2>Create Account</h2>

      <form id="signupForm" action="./signup-logic.php" method="POST">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?= $fullname ?>" placeholder="John Doe" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= $email ?>" placeholder="yourname@domain.com" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="pwd" value="<?= $pwd ?>" placeholder="Create a strong password"
          required>

        <label for="role">Role</label>
        <select name="role" id="role">
          <option value="student" <?= $role === 'student' ? 'selected' : '' ?>>Student</option>
          <option value="lecturer" <?= $role === 'lecturer' ? 'selected' : '' ?>>Lecturer</option>
          <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <button type="submit">Sign Up</button>
      </form>
      <p>Already have an account? <a href="signin.php">Sign in</a></p>
    </div>

    <footer>
      <p>&copy; 2026 AttendEase | Designed for University Attendance</p>
    </footer>
  </div>
</body>

</html>