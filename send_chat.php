<?php
session_start();
include 'db_connect.php';

$user = $_SESSION['user'];
$msg = $_POST['message'];

$conn->query("INSERT INTO chat(sender, message) VALUES('$user','$msg')");
?>