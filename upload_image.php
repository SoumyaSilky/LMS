<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

if(isset($_POST['upload'])){
    $img = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "uploads/".$img);

    $conn->query("UPDATE users SET image='$img' WHERE email='$user'");

    header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Image</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="main">

<h2>Upload Profile Image</h2>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="image" required>
    <br><br>
    <button name="upload" class="btn-success">Upload</button>
</form>

</div>

</body>
</html>