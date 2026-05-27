<?php
session_start();
include 'db_connect.php';

$teacher_id = $_GET['teacher_id'] ?? 0;
$student_id = $_GET['user_id'] ?? 0;

if($teacher_id == 0 || $student_id == 0){
    die("Invalid request");
}

$res = $conn->query("
    SELECT * FROM messages 
    WHERE 
    (sender_id='$teacher_id' AND receiver_id='$student_id') 
    OR 
    (sender_id='$student_id' AND receiver_id='$teacher_id')
    ORDER BY id ASC
");

while($row = $res->fetch_assoc()){
    if($row['sender_id'] == $teacher_id){
        echo "<div class='message sent'>{$row['message']}</div>";
    } else {
        echo "<div class='message received'>{$row['message']}</div>";
    }
}
?>