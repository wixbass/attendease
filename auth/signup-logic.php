<?php
require_once __DIR__ . "/../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $fullname = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $pwd = trim($_POST['pwd'] ?? '');
    $position = trim($_POST['role'] ?? '');

    // ====================================================
    // verifying the inputs 
    // ====================================================
    if (empty($fullname) || empty($email) || empty($pwd)) {
        $_SESSION['signup'] = "Please fill in all empty fields.";
        $_SESSION['signup-data'] = $_POST;
        header("location: signup.php");
        die();
    }
    // ====================================================
    // validate Email
    // ====================================================

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup'] = 'please enter a valid Email.';
        $_SESSION['signup-data'] = $_POST;
        header('location:  signup.php');
        die();
    }

    // ====================================================
    // checking for the password length
    // ====================================================
    if (strlen($pwd) < 8) {
        $_SESSION['signup'] = "Password must be at least 8 characters.";
        header("location: signup.php");
        die();
    }

    try {
        // ====================================================
        // check if email already exists ...
        // ====================================================
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]); // FIXED: execute() requires an array

        if ($stmt->fetch()) {
            $_SESSION['signup'] = "Email already exists.";
            header("location: signup.php");
            die();
        }

        // ====================================================
        // hash the password ...
        // ====================================================

        $hashedPassword = password_hash($pwd, PASSWORD_DEFAULT);

        // ====================================================
        // insert user into the database ...
        // ====================================================
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, pwd, position) VALUES (:fullname, :email, :pwd , :position)");

        $stmt->execute([
            ":fullname" => $fullname,
            ":email" => $email,
            ":pwd" => $hashedPassword,
            ":position" => $position

        ]);

        // ====================================================
        // success message...
        // ====================================================
        $_SESSION['signup-success'] = "Account created successfully!";
        header("Location: signin.php");
        exit();


    } catch (PDOException $e) {
        $_SESSION['signup'] = "Database error: " . $e->getMessage();
        $_SESSION['signup-data'] = $_POST;
        header("location: signup.php");
        die();
    }

} else {
    header("location: signup.php");
    die();
}