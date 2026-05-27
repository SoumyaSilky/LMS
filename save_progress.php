<?php
include 'db_connect.php';

$user_id = 1; // replace with session later
$course_id = $_POST['course_id'];
$progress = $_POST['progress'];

$conn->query("
INSERT INTO progress(user_id,course_id,progress)
VALUES('$user_id','$course_id','$progress')
ON DUPLICATE KEY UPDATE progress='$progress'
");