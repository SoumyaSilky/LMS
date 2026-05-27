<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$u = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();

if(isset($_POST['update'])){
    $name = $_POST['name'];

    $conn->query("UPDATE users SET name='$name' WHERE email='$user'");

    header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="main">

<h2>Edit Profile</h2>

<form method="post">
    <input type="text" name="name" value="<?php echo $u['name']; ?>" required>
    <br><br>
    <button name="update" class="btn-primary">Update</button>
</form>

</div>

</body>
</html>