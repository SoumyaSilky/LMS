<?php
session_start();
include 'db_connect.php';

$user = $_SESSION['user'];
$student = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$student_id = $student['id'];

$msg = $_POST['msg'];
$teacher_id = $_POST['teacher_id'];

if($msg != ''){
    $conn->query("
        INSERT INTO messages (sender_id, receiver_id, message)
        VALUES ('$student_id', '$teacher_id', '$msg')
    ");
}
?>