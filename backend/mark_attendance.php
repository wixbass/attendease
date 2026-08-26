<?php

require_once __DIR__ . "/../config/database.php";

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    die("You must be logged in as a student to mark attendance.");
}

// Get token from QR scan
$token = $_GET['token'] ?? '';
if (empty($token)) {
    die("Invalid request. No token provided.");
}

// Look up session by token
$stmt = $conn->prepare("SELECT * FROM sessions WHERE session_code = :token AND expiry_time > NOW() AND status = 'open'");
$stmt->execute([':token' => $token]);
$session = $stmt->fetch();

if (!$session) {
    die("QR expired or invalid.");
}

// Check if student already marked attendance
$stmt = $conn->prepare("SELECT * FROM attendance WHERE student_id = :student_id AND session_id = :session_id");
$stmt->execute([
    ':student_id' => $_SESSION['student_id'],
    ':session_id' => $session['id']
]);

if ($stmt->fetch()) {
    die("Attendance already marked for this session.");
}

// Insert attendance record
$stmt = $conn->prepare("INSERT INTO attendance (student_id, session_id, timestamp) 
                        VALUES (:student_id, :session_id, NOW())");
$stmt->execute([
    ':student_id' => $_SESSION['student_id'],
    ':session_id' => $session['id']
]);

echo "Attendance marked successfully!";
?>
