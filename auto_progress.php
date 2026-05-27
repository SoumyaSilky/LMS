<?php
include 'db_connect.php';
session_start();

$user = $_SESSION['user'];
$u = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$user_id = $u['id'];

$course_id = $_GET['course_id'];
$progress = $_GET['progress'];

$conn->query("
INSERT INTO progress(user_id,course_id,progress)
VALUES('$user_id','$course_id','$progress')
ON DUPLICATE KEY UPDATE progress='$progress'
");

echo $progress;