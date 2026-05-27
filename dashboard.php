<?php
session_start();
include 'db_connect.php';

// CHECK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// GET USER DATA
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$u = $result->fetch_assoc();

if(!$u){
    die("User not found");
}

$user_id = $u['id'];

// TOTAL COURSES
$total_courses = $conn->query("SELECT COUNT(*) as c FROM courses")->fetch_assoc()['c'];

// MY COURSES
$my_courses = $conn->query("
    SELECT COUNT(*) as c 
    FROM enrollments 
    WHERE user_id='$user_id'
")->fetch_assoc()['c'];

// ENROLLED IDS
$enrolled_ids = [];
$enrolled = $conn->query("SELECT course_id FROM enrollments WHERE user_id='$user_id'");
while($row = $enrolled->fetch_assoc()){
    $enrolled_ids[] = $row['course_id'];
}

// NOTIFICATION COUNT
$count = $conn->query("
    SELECT COUNT(*) as c 
    FROM notifications 
    WHERE user_id='$user_id' AND status='unread'
")->fetch_assoc()['c'];

// ALL COURSES
$courses = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html>
<head>
<title>LMS Dashboard</title>
<link rel="stylesheet" href="css/style.css">
<script src="js/script.js" defer></script>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <!-- TOP -->
    <div class="top">
        <h2 class="logo">  💻 LMS </h2>
        <span class="toggle-btn" onclick="toggleSidebar()">☰</span>
    </div>

    <!-- MENU -->
    <ul>
        <li onclick="go('dashboard.php')"><span>🏠</span> <p>Dashboard</p></li>
        <li onclick="go('profile.php')"><span>👤</span> <p>Profile</p></li>
        <li onclick="go('assignment.php')"><span>📝</span> <p>Assignments</p></li>
        <li onclick="go('chat.php')"><span>💬</span> <p>Chat</p></li>
        <li onclick="go('notice.php')"><span>📢</span> <p>Notice</p></li>
        <li onclick="go('student_result.php')"><span>📊</span> <p>Result</p></li>
        <li onclick="go('timetable.php')"><span>📅</span> <p>Timetable</p></li>
        <li onclick="go('exam_timetable.php')"><span>🧾</span> <p>Exam Timetable</p></li>
        <li onclick="go('payment.php')"><span>💳</span> <p>Payment</p></li>
        <li onclick="go('resources.php')"><span>📥</span> <p>Resources</p></li>
        <li onclick="go('attendance.php')"><span>✅</span> <p>Attendance</p></li>
        <li onclick="go('search.php')"><span>🔍</span> <p>Search More Courses</p></li>
        <li onclick="go('leaderboard.php')"><span>🏆</span> <p>Leaderboard</p></li>
        <li onclick="go('logout.php')"><span>🚪</span> <p>Logout</p></li>
    </ul>

</div>

<!-- MAIN -->
<div class="main">

<!-- HERO -->
<div class="hero-box">

    <div class="left-info">

        <div>
            <h2 class="welcome-heading">
                WELCOME TO PROGRAMMING ADDA, <?php echo $u['name']; ?> 
            </h2>
            <p class="subtitle">Let’s continue learning today</p>
        </div>
    </div>

    <div class="right-menu">

        <!-- 🔔 NOTIFICATION -->
        <div class="notif-box">

            <div class="notif-icon" onclick="toggleNotif(event)">
                🔔
                <?php if($count > 0){ ?>
                <span class="notif-count"><?php echo $count; ?></span>
                <?php } ?>
            </div>

            <div class="notif-dropdown" id="notifBox">
                <h4>Notifications</h4>

                <?php
                $noti = $conn->query("SELECT * FROM notifications WHERE user_id='$user_id' ORDER BY id DESC LIMIT 5");

                if($noti->num_rows > 0){
                    while($n = $noti->fetch_assoc()){
                        echo "<p>".$n['message']."</p>";
                    }
                } else {
                    echo "<p>No notifications</p>";
                }
                ?>
            </div>

        </div>

        <!-- DARK MODE -->
        <button class="dark-btn" onclick="toggleDark()">🌙</button>

        <!-- PROFILE -->
        <div class="profile-menu">
            <img src="uploads/<?php echo $u['image']; ?>" class="avatar" onclick="toggleMenu()">

            <div class="dropdown" id="dropdown">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

    </div>

</div>

<div style="margin-top:20px;"></div>

<!-- STATS -->
<div class="stats">
    <div class="stat-box">
        <p>Total Courses</p>
        <h2><?php echo $total_courses; ?></h2>
    </div>

    <div class="stat-box">
        <p>My Courses</p>
        <h2><?php echo $my_courses; ?></h2>
    </div>
</div>

<!-- COURSES -->
<h2 class="section-title">All Courses</h2>

<div class="courses">

<?php while($c = $courses->fetch_assoc()){ 

    $pid = $conn->query("
        SELECT progress FROM progress 
        WHERE user_id='$user_id' AND course_id='{$c['id']}'
    ")->fetch_assoc();

    $prog = $pid ? $pid['progress'] : 0;
?>

<div class="card">
    <img src="uploads/<?php echo $c['image']; ?>" class="course-img">
    
    <div class="card-body">
        <h3><?php echo $c['title']; ?></h3>

        <?php if($prog == 100){ ?>
            <span class="badge" style="background:gold;color:black;">🏆 Completed</span>
        <?php } ?>

        <?php if(in_array($c['id'], $enrolled_ids)){ ?>

            <span class="badge">Enrolled</span>

            <div class="progress">
                <div class="progress-bar" style="width:<?php echo $prog; ?>%"></div>
            </div>

            <p class="progress-text"><?php echo $prog; ?>% Completed</p>

            <div class="card-btns">
                <a class="btn-primary" href="course.php?id=<?php echo $c['id']; ?>">Open</a>
                <a class="btn-danger" href="unenroll.php?id=<?php echo $c['id']; ?>">Unenroll</a>
            </div>

        <?php } else { ?>

            <a class="btn-success" href="enroll.php?id=<?php echo $c['id']; ?>">Enroll</a>

        <?php } ?>
    </div>
</div>

<?php } ?>

</div>

</div>
<script src="js/script.js"></script>
</body>
</html>