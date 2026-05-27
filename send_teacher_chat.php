<?php
session_start();
include 'db_connect.php';

$msg = $_POST['msg'];
$teacher_id = $_POST['teacher_id'];
$student_id = $_POST['user_id'];

if($msg != ''){
    $conn->query("
        INSERT INTO messages (sender_id, receiver_id, message)
        VALUES ('$teacher_id', '$student_id', '$msg')
    ");
}
?>