<?php
session_start();
include 'db_connect.php';

// CHECK ADMIN LOGIN
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// TOTAL USERS
$total_users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'] ?? 0;

// TOTAL STUDENTS
$total_students = $conn->query("
SELECT COUNT(*) as c FROM users WHERE role='student'
")->fetch_assoc()['c'] ?? 0;

// TOTAL TEACHERS
$total_teachers = $conn->query("
SELECT COUNT(*) as c FROM users WHERE role='teacher'
")->fetch_assoc()['c'] ?? 0;

// TOTAL COURSES
$total_courses = $conn->query("
SELECT COUNT(*) as c FROM courses
")->fetch_assoc()['c'] ?? 0;

// TOTAL REVENUE
$total_revenue = $conn->query("
SELECT SUM(amount) as total FROM payments
")->fetch_assoc()['total'] ?? 0;

// PENDING COURSES (optional approval system)
$pending_courses = $conn->query("
SELECT COUNT(*) as c FROM courses WHERE status='pending'
")->fetch_assoc()['c'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>

body{
    margin:0;
    font-family:'Segoe UI';
    background:#f1f5f9;
}

/* SIDEBAR */
.sidebar{
    width:250px;
    height:100vh;
    background:#020617;
    color:white;
    position:fixed;
    padding:20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:20px;
}

.sidebar a{
    display:block;
    padding:12px;
    margin:8px 0;
    color:#cbd5f5;
    text-decoration:none;
    border-radius:8px;
}

.sidebar a:hover{
    background:#1e293b;
}

/* MAIN */
.main{
    margin-left:270px;
    padding:20px;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    border-radius:15px;
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
    <h2>Admin</h2>

    <a href="admin.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_courses.php">Manage Courses</a>
    <a href="manage_payments.php">Manage Payments</a>
    <a href="approve_courses.php">Course Approvals</a>
    <a href="reports.php">Reports</a>
    <a href="publish_result_pro.php">Publish Result</a>
    <a href="manage_notice.php">Manage Notice</a>
    <a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="header">
    <h2>Welcome Admin </h2>
    <p>Manage your LMS system efficiently</p>
</div>

<!-- STATS -->
<div class="cards">

<div class="card">
    <h3>Total Users</h3>
    <h2><?php echo $total_users; ?></h2>
</div>

<div class="card">
    <h3>Students</h3>
    <h2><?php echo $total_students; ?></h2>
</div>

<div class="card">
    <h3>Teachers</h3>
    <h2><?php echo $total_teachers; ?></h2>
</div>

<div class="card">
    <h3>Courses</h3>
    <h2><?php echo $total_courses; ?></h2>
</div>

<div class="card">
    <h3>Revenue</h3>
    <h2>₹ <?php echo $total_revenue ?: 0; ?></h2>
</div>

<div class="card">
    <h3>Pending Course Approvals</h3>
    <h2><?php echo $pending_courses; ?></h2>
</div>

</div>

<!-- RECENT ACTIVITY -->
<div class="activity">
    <h3> Recent Activity</h3>

    <?php
    $recent = $conn->query("
        SELECT name FROM users ORDER BY id DESC LIMIT 5
    ");

    if($recent->num_rows > 0){
        while($r = $recent->fetch_assoc()){
            echo "<p>👤 New User Registered: ".$r['name']."</p>";
        }
    } else {
        echo "<p>No recent activity</p>";
    }
    ?>
</div>

</div>

</body>
</html>

