<?php
session_start();
include 'db_connect.php';

$user = $_SESSION['user'];
$u = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$user_id = $u['id'];

$course_id = $_GET['course_id'];

// GET CURRENT PROGRESS
$p = $conn->query("
SELECT * FROM progress 
WHERE user_id='$user_id' AND course_id='$course_id'
")->fetch_assoc();

$current = $p['progress'];

// INCREASE BY 10%
$new = $current + 10;

if($new > 100) $new = 100;

// UPDATE
$conn->query("
UPDATE progress 
SET progress='$new' 
WHERE user_id='$user_id' AND course_id='$course_id'
");

// RETURN NEW VALUE
echo $new;
?>