<?php
include 'db_connect.php';

$id = $_POST['id'];
$marks = $_POST['marks'];
$feedback = $_POST['feedback'];

$conn->query("
UPDATE assignments 
SET marks='$marks', 
    feedback='$feedback', 
    status='Checked'
WHERE id='$id'
");

header("Location: teacher.php");
?>