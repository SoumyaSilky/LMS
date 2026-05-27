<?php
session_start();
include 'db_connect.php';

$user = $_SESSION['user'];
$u = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$user_id = $u['id'];

$aid = $_POST['assignment_id'];

$file = $_FILES['file']['name'];
$tmp = $_FILES['file']['tmp_name'];

move_uploaded_file($tmp, "uploads/".$file);

$conn->query("INSERT INTO submissions (assignment_id, user_id, file, status)
              VALUES ('$aid','$user_id','$file','Submitted')");

header("Location: assignment.php");
?>