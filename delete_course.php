<?php
include 'db_connect.php';

$id = $_GET['id'];

$conn->query("DELETE FROM courses WHERE id='$id'");

header("Location: manage_courses.php");
?>