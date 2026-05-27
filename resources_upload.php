<?php
session_start();
include 'db_connect.php';

$msg = "";

if(isset($_POST['upload'])){
    $title = $_POST['title'];

    $file = $_FILES['file']['name'];
    $tmp  = $_FILES['file']['tmp_name'];

    move_uploaded_file($tmp, "uploads/resources/".$file);

    $conn->query("INSERT INTO resources(title, file) VALUES('$title','$file')");

    $msg = "✅ Resource Uploaded!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Resource</title>

<style>
body{
    font-family:Segoe UI;
    background:#f1f5f9;
}

.box{
    width:400px;
    margin:100px auto;
    padding:25px;
    background:white;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

input, button{
    width:100%;
    padding:10px;
    margin-top:10px;
    border-radius:8px;
}

button{
    background:#4f46e5;
    color:white;
    border:none;
}
</style>

</head>

<body>

<div class="box">
<h2>Upload Resource</h2>

<?php if($msg) echo "<p>$msg</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required>
    <input type="file" name="file" required>
    <button name="upload">Upload</button>
</form>

</div>

</body>
</html>