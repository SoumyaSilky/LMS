<?php
include 'db_connect.php';

$title = $_POST['title'];
$desc  = $_POST['desc'];

$conn->query("
INSERT INTO courses(title,description)
VALUES('$title','$desc')
");

header("Location: admin.php");
?>