<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$u = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$user_id = $u['id'];

// STATS
$total_courses = $conn->query("SELECT COUNT(*) as c FROM courses")->fetch_assoc()['c'];
$my_courses = $conn->query("SELECT COUNT(*) as c FROM enrollments WHERE user_id='$user_id'")->fetch_assoc()['c'];
$completed = $conn->query("SELECT COUNT(*) as c FROM progress WHERE user_id='$user_id' AND progress=100")->fetch_assoc()['c'];

// PROGRESS AVG
$avg = $conn->query("SELECT AVG(progress) as p FROM progress WHERE user_id='$user_id'")->fetch_assoc()['p'];
$avg = round($avg ?? 0);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link rel="stylesheet" href="css/style.css">

<style>

/* ===== CONTAINER ===== */
.profile-container{
    max-width:1000px;
    margin:40px auto;
}

/* ===== CARD ===== */
.profile-card{
    background:white;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    overflow:hidden;
}

/* ===== COVER ===== */
.profile-cover{
    height:180px;
    background:linear-gradient(135deg,#4e73df,#1cc88a);
}

/* ===== INFO ===== */
.profile-info{
    text-align:center;
    padding:20px;
    position:relative;
}

.profile-avatar{
    width:120px;
    height:120px;
    border-radius:50%;
    border:5px solid white;
    object-fit:cover;
    position:absolute;
    top:-60px;
    left:50%;
    transform:translateX(-50%);
}

.profile-name{
    margin-top:70px;
    font-size:26px;
    font-weight:bold;
}

.profile-email{
    color:#777;
}

/* ===== BUTTONS ===== */
.profile-actions{
    margin-top:15px;
}

.btn{
    padding:8px 15px;
    border-radius:8px;
    text-decoration:none;
    margin:5px;
    display:inline-block;
}

.btn-primary{background:#4e73df;color:white;}
.btn-success{background:#1cc88a;color:white;}
.btn-danger{background:#ff4d4d;color:white;}

/* ===== STATS ===== */
.profile-stats{
    display:flex;
    justify-content:space-around;
    padding:20px;
    border-top:1px solid #eee;
}

.stat{
    text-align:center;
}

.stat h2{
    color:#4e73df;
}

/* ===== PROGRESS ===== */
.progress-box{
    padding:20px;
}

.progress{
    background:#eee;
    height:10px;
    border-radius:10px;
}

.progress-bar{
    height:100%;
    background:#4e73df;
}

/* ===== BADGES ===== */
.badges{
    padding:20px;
}

.badge-item{
    display:inline-block;
    background:#f1f5f9;
    padding:8px 12px;
    border-radius:20px;
    margin:5px;
}

/* ===== ACTIVITY ===== */
.activity{
    padding:20px;
}

.activity p{
    padding:8px 0;
    border-bottom:1px solid #eee;
}

/* DARK MODE */
.dark-mode .profile-card{
    background:#1e293b;
    color:white;
}
.dark-mode .badge-item{
    background:#334155;
}

</style>

</head>

<body>

<div class="main">

<div class="profile-container">

<div class="profile-card">

    <!-- COVER -->
    <div class="profile-cover"></div>

    <!-- INFO -->
    <div class="profile-info">

        <img src="uploads/<?php echo $u['image']; ?>" class="profile-avatar">

        <h2 class="profile-name"><?php echo $u['name']; ?></h2>
        <p class="profile-email"><?php echo $u['email']; ?></p>

        <div class="profile-actions">
            <a href="edit_profile.php" class="btn btn-primary">✏️ Edit</a>
            <a href="upload_image.php" class="btn btn-success">📸 Change Photo</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

    </div>

    <!-- STATS -->
    <div class="profile-stats">
        <div class="stat">
            <h2><?php echo $total_courses; ?></h2>
            <p>Total Courses</p>
        </div>
        <div class="stat">
            <h2><?php echo $my_courses; ?></h2>
            <p>Enrolled</p>
        </div>
        <div class="stat">
            <h2><?php echo $completed; ?></h2>
            <p>Completed</p>
        </div>
    </div>

    <!-- PROGRESS -->
    <div class="progress-box">
        <p>Overall Progress</p>
        <div class="progress">
            <div class="progress-bar" style="width:<?php echo $avg; ?>%"></div>
        </div>
        <p><?php echo $avg; ?>% Completed</p>
    </div>

    <!-- BADGES -->
    <div class="badges">
        <h3> Achievements</h3>

        <?php if($completed >= 1){ ?>
            <span class="badge-item">🎓 First Course Completed</span>
        <?php } ?>

        <?php if($completed >= 5){ ?>
            <span class="badge-item">🔥 Learning Streak</span>
        <?php } ?>

        <?php if($avg == 100){ ?>
            <span class="badge-item">💯 Perfectionist</span>
        <?php } ?>

    </div>

    <!-- ACTIVITY -->
    <div class="activity">
        <h3>Recent Activity</h3>

        <?php
        $act = $conn->query("SELECT * FROM enrollments WHERE user_id='$user_id' ORDER BY id DESC LIMIT 5");

        if($act->num_rows > 0){
            while($a = $act->fetch_assoc()){
                echo "<p> Enrolled in course ID: ".$a['course_id']."</p>";
            }
        } else {
            echo "<p>No activity yet</p>";
        }
        ?>
    </div>

</div>

</div>

</div>

</body>
</html>