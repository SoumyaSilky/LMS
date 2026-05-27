<?php
include 'db_connect.php';

$courses = $conn->query("SELECT * FROM courses");

if(isset($_POST['upload'])){

    $title = $_POST['title'];
    $desc = $_POST['description'];
    $course_id = $_POST['course_id'];
    $deadline = $_POST['deadline'];

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];

    $path = "uploads/".$file;
    move_uploaded_file($tmp, $path);

    $conn->query("INSERT INTO assignments 
        (title,description,file,course_id,deadline)
        VALUES ('$title','$desc','$file','$course_id','$deadline')");

    echo "<script>alert('Assignment Uploaded Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Assignment</title>

<style>
body{
    background:linear-gradient(120deg,#667eea,#764ba2);
    font-family:sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.card{
    background:white;
    padding:30px;
    width:400px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}

input,textarea,select{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:12px;
    background:#667eea;
    color:white;
    border:none;
    border-radius:8px;
}
</style>
</head>

<body>

<div class="card">
<h2>Upload Assignment</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" placeholder="Assignment Title" required>

<textarea name="description" placeholder="Description"></textarea>

<select name="course_id" required>
<option value="">Select Course</option>
<?php while($c = $courses->fetch_assoc()){ ?>
<option value="<?= $c['id'] ?>"><?= $c['title'] ?></option>
<?php } ?>
</select>

<!-- ✅ NEW DEADLINE FIELD -->
<label><b>Deadline:</b></label>
<input type="datetime-local" name="deadline" required>

<input type="file" name="file" required>

<button name="upload">Upload Assignment</button>

</form>
</div>

</body>
</html>