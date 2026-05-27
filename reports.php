<?php
session_start();
include 'db_connect.php';

// TOTAL USERS
$total_users = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'] ?? 0;

// TOTAL COURSES
$total_courses = $conn->query("SELECT COUNT(*) as c FROM courses")->fetch_assoc()['c'] ?? 0;

// TOTAL ENROLLMENTS
$total_enroll = $conn->query("SELECT COUNT(*) as c FROM enrollments")->fetch_assoc()['c'] ?? 0;

// TOTAL REVENUE
$total_revenue = $conn->query("SELECT SUM(amount) as total FROM payments")->fetch_assoc()['total'] ?? 0;

// RECENT USERS
$recent_users = $conn->query("SELECT name FROM users ORDER BY id DESC LIMIT 5");

// RECENT COURSES
$recent_courses = $conn->query("SELECT title FROM courses ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
<title>Reports Dashboard</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    font-size:22px;
    font-weight:bold;
}

/* GRID */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    padding:20px;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    color:#64748b;
    margin-bottom:10px;
}

.card h2{
    margin:0;
}

/* PROGRESS BAR */
.progress{
    margin-top:10px;
    background:#e5e7eb;
    border-radius:10px;
    overflow:hidden;
}

.bar{
    height:8px;
    background:#4f46e5;
}

/* SECTIONS */
.section{
    padding:20px;
}

.box{
    background:white;
    padding:15px;
    border-radius:12px;
    margin-bottom:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.box h4{
    margin-bottom:10px;
}

/* LIST */
.item{
    padding:8px;
    border-bottom:1px solid #eee;
}

.item:last-child{
    border:none;
}
</style>
</head>

<body>

<div class="header">Reports Dashboard</div>

<!-- STATS -->
<div class="cards">

<div class="card">
    <h3>Total Users</h3>
    <h2><?php echo $total_users; ?></h2>
    <div class="progress"><div class="bar" style="width:80%"></div></div>
</div>

<div class="card">
    <h3>Total Courses</h3>
    <h2><?php echo $total_courses; ?></h2>
    <div class="progress"><div class="bar" style="width:60%"></div></div>
</div>

<div class="card">
    <h3>Enrollments</h3>
    <h2><?php echo $total_enroll; ?></h2>
    <div class="progress"><div class="bar" style="width:70%"></div></div>
</div>

<div class="card">
    <h3>Revenue</h3>
    <h2>₹ <?php echo $total_revenue ?: 0; ?></h2>
    <div class="progress"><div class="bar" style="width:90%"></div></div>
</div>

</div>

<!-- RECENT DATA -->
<div class="section">

<div class="box">
    <h4> Total Users Name</h4>
    <?php if($recent_users->num_rows > 0){
        while($u = $recent_users->fetch_assoc()){
            echo "<div class='item'>".$u['name']."</div>";
        }
    } else {
        echo "<div class='item'>No users</div>";
    } ?>
</div>

<div class="box">
    <h4> Total Courses</h4>
    <?php if($recent_courses->num_rows > 0){
        while($c = $recent_courses->fetch_assoc()){
            echo "<div class='item'>".$c['title']."</div>";
        }
    } else {
        echo "<div class='item'>No courses</div>";
    } ?>
</div>

</div>

</body>
</html>