<?php
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pwd = trim($_POST['pwd']);


    // ====================================================
    // verifying the fucking inputs
    // ====================================================
    if (empty($email) || empty($pwd)) {
        $_SESSION['signin'] = "please fill in all empty fieilds";
        header("location: signin.php");
        exit;
    }

    // =========================================================
    // check the password length
    // =======================================================
    if (strlen($pwd) < 8) {
        $_SESSION['signin'] = "Password must be at least 8 characters."; $_SESSION['signin-data'] = $_POST;header("location: signin.php");
        exit;
    }


    try {
        // =========================================================
        // check if the user exit in the database...
        // =======================================================
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);

        // =========================================================
        // if the user exit then verify the password and create session for the user
        // =======================================================
        if ($user = $stmt->fetch()) {
            if (password_verify($pwd, $user['pwd'])) {

                $position = strtolower(trim($user['position'] ?? '')); $_SESSION['user-id'] = $user['id']; $_SESSION['email'] = $user['email']; $_SESSION['position'] = $position;

                // $admin_secret_passcode = 'admin_@351#';
                // $lecturer_secret_passcode = 'lecturer_@351#';
                if($position === 'lecturer' || $position === 'admin') {
                    header("Location: ../backend/generate_qr_code.php");
                    die();
                   }
               

            } else {
                $_SESSION['signin'] = "invalid email or password";$_SESSION['signin-data'] = $_POST;
                header("location: signin.php");
                exit;
            } 
        } 
        else {
            $_SESSION['signin'] = "invalid email or password";$_SESSION['signin-data'] = $_POST;
            header("location: signin.php");
            die();
        }








    } catch (PDOException $e) {

        $_SESSION['signin'] = "connection_failed: " . $e->getMessage();$_SESSION['signin-data'] = $_POST;
        header("location: signin.php");
        exit;
    }


} else {
    header("location: signin.php");
    exit;
}