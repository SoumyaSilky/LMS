<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connect.php';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if($user && password_verify($pass, $user['password'])){

        $_SESSION['user'] = $email;
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];

        // ROLE REDIRECT
        if($user['role'] == 'admin'){
            header("Location: admin.php");
        } elseif($user['role'] == 'teacher'){
            header("Location: teacher_dashboard.php"); // ✅ FIXED
        } else {
            header("Location: dashboard.php");
        }
        exit();

    } else {
        $error = "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

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

<body>

<div class="login-container">

<h2>Welcome Back</h2>

<?php if(isset($error)){ ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<form method="post">

<div class="input-box">
<input type="email" name="email" placeholder="Email" required>
</div>

<div class="input-box">
<input type="password" name="password" placeholder="Password" required>
</div>

<button class="login-btn" name="login">Login</button>

<p class="link">
Don't have an account? <a href="register.php">Register</a>
</p>

</form>

</div>

</body>
</html>