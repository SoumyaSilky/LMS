<?php
session_start();
include("db_connect.php");

$msg = "";

// UPLOAD COURSE
if(isset($_POST['upload'])){

    $title = $_POST['title'];
    $desc = $_POST['description'];

    // IMAGE UPLOAD
    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    // ✅ CHECK IMAGE SELECTED
    if(!empty($image)){

        $target = "uploads/" . basename($image);

        if(move_uploaded_file($tmp, $target)){

            $teacher_id = $_SESSION['user_id']; // VERY IMPORTANT

            $conn->query("
            INSERT INTO courses(title, description, image, teacher_id) 
            VALUES('$title','$desc','$image','$teacher_id')
            ");

            $msg = "✅ Course Uploaded Successfully!";
        } else {
            $msg = "❌ Image Upload Failed!";
        }

    } else {
        $msg = "❌ Please select an image!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Course</title>

<style>

/* ===== BODY ===== */
body{
    font-family:'Segoe UI', sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    margin:0;
}

/* ===== CONTAINER ===== */
.container{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* ===== CARD ===== */
.card{
    background:white;
    padding:30px;
    width:400px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    animation:fade 0.6s ease;
}

@keyframes fade{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1;}
}

/* ===== TITLE ===== */
.card h2{
    text-align:center;
    margin-bottom:20px;
}

/* ===== INPUT ===== */
.input-group{
    margin-bottom:15px;
}

.input-group label{
    font-weight:600;
    display:block;
    margin-bottom:5px;
}

.input-group input,
.input-group textarea{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:1px solid #ccc;
    outline:none;
    transition:0.3s;
}

.input-group input:focus,
.input-group textarea:focus{
    border-color:#4f46e5;
    box-shadow:0 0 5px rgba(79,70,229,0.3);
}

/* ===== FILE INPUT ===== */
.file-box{
    border:2px dashed #ccc;
    padding:15px;
    text-align:center;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
}

.file-box:hover{
    border-color:#4f46e5;
    background:#f1f5ff;
}

/* ===== IMAGE PREVIEW ===== */
.preview{
    margin-top:10px;
    text-align:center;
}

.preview img{
    width:100%;
    border-radius:10px;
}

/* ===== BUTTON ===== */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#4f46e5;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#3730a3;
}

/* ===== MESSAGE ===== */
.msg{
    text-align:center;
    margin-bottom:10px;
    color:green;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<div class="card">

    <h2>Upload Course</h2>

    <?php if($msg){ echo "<div class='msg'>$msg</div>"; } ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="input-group">
            <label>Course Title</label>
            <input type="text" name="title" required>
        </div>

        <div class="input-group">
            <label>Description</label>
            <textarea name="description" rows="4" required></textarea>
        </div>

        <div class="input-group">
            <label>Course Image</label>

            <div class="file-box" onclick="document.getElementById('file').click()">
                Click to upload image
                <input type="file" id="file" name="image" hidden onchange="previewImage(event)">
            </div>

            <div class="preview" id="preview"></div>
        </div>

        <button name="upload">Upload Course 🚀</button>

    </form>

</div>

</div>

<script>

// IMAGE PREVIEW
function previewImage(event){
    let reader = new FileReader();
    reader.onload = function(){
        let img = document.createElement("img");
        img.src = reader.result;

        let preview = document.getElementById("preview");
        preview.innerHTML = "";
        preview.appendChild(img);
    }
    reader.readAsDataURL(event.target.files[0]);
}

</script>

</body>
</html>