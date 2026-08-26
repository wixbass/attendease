<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/attendease/config/database.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AttendEase - University Attendance System</title>


  <link rel="icon"  type="image/png" href="/attendease/assets/images/my_logo.png">


  <link rel="stylesheet" href="/attendease/assets/css/style.css">
  <link rel="stylesheet" href="/attendease/assets/css/responsive.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <header id="header">
    <div class="logo">AttendEase</div>
    <nav>
      <ul>
        <li><a href="/attendease/index.php">HOME</a></li><li><a href="/attendease/about.php">ABOUT</a></li><li><a href="/attendease/services.php">SERVICES</a></li><li><a href="/attendease/contact.php">CONTACT</a></li><li><a href="/attendease/qr.php">QR ATTENDANCE</a></li>
        <li>
          <a href="/attendease/auth/signin.php"
            style="color:#1abc9c; margin-left:15px; font-size:1.2em; font-weight:600; background-color:#105245dc; padding:12px 55px; border-radius:6px; max-width:fit-content;">
            Sign In
          </a>
        </li>
      </ul>
    </nav>
    <div class="menu-toggle" id="menu-toggle1" aria-label="Open Menu">&#9776;</div>
    <div class="menu-toggle" id="menu-toggle2" aria-label="Close Menu"><i class="fa-sharp fa-solid fa-xmark"></i></div>
  </header>