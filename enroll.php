<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    die("Course ID missing");
}

$course_id = $_GET['id'];
$user = $_SESSION['user'];

// Get user id
$u = $conn->query("SELECT id FROM users WHERE email='$user'")->fetch_assoc();
$user_id = $u['id'];

// Insert enrollment
$conn->query("INSERT INTO enrollments(user_id, course_id) VALUES('$user_id', '$course_id')");

// Redirect back
header("Location: dashboard.php");
exit();
?>