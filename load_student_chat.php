<?php
session_start();
include 'db_connect.php';

$user = $_SESSION['user'];
$student = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$student_id = $student['id'];

$teacher_id = $_GET['teacher_id'];

$res = $conn->query("
    SELECT * FROM messages 
    WHERE 
    (sender_id='$student_id' AND receiver_id='$teacher_id') 
    OR 
    (sender_id='$teacher_id' AND receiver_id='$student_id')
    ORDER BY id ASC
");

while($row = $res->fetch_assoc()){
    
    if($row['sender_id'] == $student_id){
        echo "<div class='message sent'>{$row['message']}</div>";
    } else {
        echo "<div class='message received'>{$row['message']}</div>";
    }
}
?>