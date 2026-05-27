<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

if(isset($_POST['register'])){
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role  = $_POST['role']; // ✅ NEW

    // IMAGE UPLOAD
    $image = $_FILES['image']['name'];
    $tmp   = $_FILES['image']['tmp_name'];

    if($image != ""){
        move_uploaded_file($tmp, "uploads/".$image);
    } else {
        $image = "default.png";
    }

    // ✅ INSERT WITH ROLE
    $stmt = $conn->prepare("INSERT INTO users(name,email,password,image,role) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss",$name,$email,$pass,$image,$role);
    $stmt->execute();

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

/* BACKGROUND */
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f') no-repeat center center/cover;
}

/* DARK OVERLAY */
body::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    z-index:0;
}

/* LOGIN CARD */
.login-container{
    position:relative;
    z-index:1;
    width:350px;
    padding:30px;
    border-radius:20px;
    background:rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    box-shadow:0 10px 40px rgba(0,0,0,0.3);
    text-align:center;
    animation:fade 0.6s ease;
}

/* ANIMATION */
@keyframes fade{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1;}
}

/* TITLE */
.login-container h2{
    color:#fff;
    margin-bottom:20px;
}

/* INPUT BOX */
.input-box{
    margin-bottom:15px;
}

.input-box input{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    outline:none;
    background:rgba(255,255,255,0.2);
    color:white;
    font-size:14px;
}

.input-box input::placeholder{
    color:#ddd;
}

/* BUTTON */
.login-btn{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#6366f1,#7c3aed);
    border:none;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

.login-btn:hover{
    transform:scale(1.05);
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* LINK */
.link{
    margin-top:15px;
    color:#eee;
    font-size:14px;
}

.link a{
    color:#a5b4fc;
    text-decoration:none;
}

.link a:hover{
    text-decoration:underline;
}

/* ERROR */
.error{
    background:#ef4444;
    color:white;
    padding:8px;
    border-radius:8px;
    margin-bottom:10px;
}

</style>
</head>

<body class="login-body">

<div class="login-container">

<h2>Create Account</h2>

<form method="post" enctype="multipart/form-data">

<div class="input-box">
<input type="text" name="name" placeholder="Full Name" required>
</div>

<div class="input-box">
<input type="email" name="email" placeholder="Email" required>
</div>

<div class="input-box">
<input type="password" name="password" placeholder="Password" required>
</div>

<!-- ✅ ROLE SELECT -->
<div class="input-box">
<select name="role" required>
    <option value="">Select Role</option>
    <option value="student">👨‍🎓 Student</option>
    <option value="teacher">👨‍🏫 Teacher</option>
    <option value="admin">👨 Admin</option>
</select>
</div>

<div class="input-box">
<input type="file" name="image">
</div>

<button class="login-btn" name="register">Register</button>

<p class="link">
Already have account? <a href="login.php">Login</a>
</p>

</form>

</div>

</body>
</html>