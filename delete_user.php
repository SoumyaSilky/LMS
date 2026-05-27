<?php
include 'db_connect.php';

$id = $_GET['id'];

$conn->query("DELETE FROM users WHERE id='$id'");

header("Location: manage_users.php");
?>