<?php
session_start();
include 'db_connect.php';

$user = $_SESSION['user'];
$student = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$student_id = $student['id'];

// assume teacher_id is fixed or passed
$teacher_id = 1; // 🔥 change if dynamic
?>