<?php
session_start();
include 'db_connect.php';

// CHECK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// GET USER ID
$email = $_SESSION['user'];
$user = $conn->query("SELECT * FROM users WHERE email='$email'")->fetch_assoc();
$user_id = $user['id'];

// GET DATA
$course_id = $_POST['course_id'];
$amount = $_POST['amount'];

// INSERT PAYMENT
$conn->query("INSERT INTO payments (user_id, course_id, amount, status) 
VALUES ('$user_id', '$course_id', '$amount', 'paid')");

// AUTO ENROLL USER
$conn->query("INSERT INTO enrollments (user_id, course_id) 
VALUES ('$user_id', '$course_id')");

// REDIRECT
header("Location: dashboard.php?payment=success");
?>