<?php
session_start();
include 'db_connect.php';

// CHECK LOGIN
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'teacher'){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];

// GET TEACHER DATA
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$res = $stmt->get_result();
$teacher = $res->fetch_assoc();

$teacher_id = $teacher['id'] ?? 0;

// TOTAL COURSES (FIXED)
$total_courses = $conn->query("
    SELECT COUNT(*) as c 
    FROM courses 
    WHERE teacher_id='$teacher_id'
")->fetch_assoc()['c'] ?? 0;

// TOTAL STUDENTS
$total_students = $conn->query("
    SELECT COUNT(DISTINCT user_id) as c 
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    WHERE c.teacher_id='$teacher_id'
")->fetch_assoc()['c'] ?? 0;

// TOTAL ASSIGNMENTS
$total_assignments = $conn->query("
    SELECT COUNT(*) as c 
    FROM assignments a
    JOIN courses c ON a.course_id = c.id
    WHERE c.teacher_id='$teacher_id'
")->fetch_assoc()['c'] ?? 0;

// TOTAL EARNINGS (FAKE / DEMO)
$earnings = $conn->query("
    SELECT SUM(amount) as total 
    FROM payments p
    JOIN courses c ON p.course_id = c.id
    WHERE c.teacher_id='$teacher_id'
")->fetch_assoc()['total'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Dashboard Pro</title>

<style>

body{
    margin:0;
    font-family:'Segoe UI';
    background:#f1f5f9;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#0f172a;
    color:white;
    position:fixed;
    padding:20px;
}

.sidebar h2{
    text-align:center;
}

.sidebar a{
    display:block;
    padding:12px;
    margin:10px 0;
    color:#cbd5f5;
    text-decoration:none;
    border-radius:8px;
}

.sidebar a:hover{
    background:#1e293b;
}

/* MAIN */
.main{
    margin-left:260px;
    padding:20px;
}

/* HEADER */
.header{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-top:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    margin:0;
    color:#64748b;
}

.card h2{
    margin-top:10px;
}

/* ACTION BUTTONS */
.actions{
    margin-top:25px;
    display:flex;
    gap:15px;
}

.actions a{
    padding:12px 20px;
    background:linear-gradient(135deg,#6366f1,#4f46e5);
    color:white;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}

.actions a:hover{
    transform:scale(1.05);
}

/* ACTIVITY */
.activity{
    margin-top:30px;
    background:white;
    padding:20px;
    border-radius:15px;
}

.activity h3{
    margin-bottom:15px;
}

.activity p{
    background:#f8fafc;
    padding:10px;
    border-radius:8px;
    margin:8px 0;
}

</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>Teacher</h2>

    <a href="teacher_dashboard.php">Dashboard</a>
    <a href="upload_course.php">Upload Course</a>
    <a href="upload_assignment.php">Upload Assignments</a>
    <a href="teacher.php">Check Assignments</a>
    <a href="teacher_attendance.php">Mark Attendance</a>
    <a href="resources_upload.php">Upload Notes</a>
    <a href="chat.php"> 💬 Chat</a>
    <a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
    <h2>Welcome, <?php echo $teacher['name']; ?> </h2>
</div>

<!-- STATS -->
<div class="cards">

    <div class="card">
        <h3>Total Courses</h3>
        <h2><?php echo $total_courses; ?></h2>
    </div>

    <div class="card">
        <h3>Total Students</h3>
        <h2><?php echo $total_students; ?></h2>
    </div>

    <div class="card">
        <h3>Assignments</h3>
        <h2><?php echo $total_assignments; ?></h2>
    </div>

    <div class="card">
        <h3>Earnings</h3>
        <h2>₹ <?php echo $earnings ?: 0; ?></h2>
    </div>

</div>

<!-- ACTION BUTTONS -->
<div class="actions">
    <a href="upload_course.php">Add Course</a>
    <a href="teacher.php">Check Work</a>
    <a href="teacher_attendance.php"> Mark Attendance</a>
    <a href="resources_upload.php">Upload Notes</a>
</div>

<!-- ACTIVITY -->
<div class="activity">
    <h3> Recent Activity</h3>

    <?php
    $recent = $conn->query("
        SELECT title FROM courses 
        WHERE teacher_id='$teacher_id' 
        ORDER BY id DESC LIMIT 5
    ");

    if($recent->num_rows > 0){
        while($r = $recent->fetch_assoc()){
            echo "<p> New Course Added: ".$r['title']."</p>";
        }
    } else {
        echo "<p>No activity yet</p>";
    }
    ?>
</div>

</div>

</body>
</html>