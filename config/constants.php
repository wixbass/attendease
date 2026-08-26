<?php
session_start();
//database connection=============
// define('ROOT_URL', $protocol . $_SERVER['HTTP_HOST'] . '/attendease/');

// define("DB_HOST", "localhost");
// define("DB_USER", "root");
// define("DB_PASS", "");
// define("DB_NAME", "attendease");

// // ... the rest of your settings

// // Site settings
define("SITE_NAME", "attendease");
define("BASE_URL", "http://localhost/attendease/");

// // QR code settings
// define("QR_EXPIRY_MINUTES", 30);      // QR validity duration
// define("QR_FOLDER", "assets/qrcodes/");

// // Contact email -> for form submission
define("ADMIN_EMAIL", "lelmicox@gmail.com");