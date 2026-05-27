<?php
session_start();
include 'db_connect.php';

$id = $_GET['id'];
$user = $_SESSION['user'];

$u = $conn->query("SELECT id FROM users WHERE email='$user'")->fetch_assoc();

$conn->query("DELETE FROM enrollments 
WHERE user_id='".$u['id']."' AND course_id='$id'");

header("Location: dashboard.php");