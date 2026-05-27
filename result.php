<?php
session_start();
include 'db_connect.php';

$email = $_SESSION['user'];
$user = $conn->query("SELECT * FROM users WHERE email='$email'")->fetch_assoc();
$uid = $user['id'];

$results = $conn->query("
SELECT r.*, s.subject_name, c.title 
FROM results r
JOIN subjects s ON r.subject_id = s.id
JOIN courses c ON r.course_id = c.id
WHERE r.user_id='$uid'
ORDER BY semester
");

// CGPA CALCULATION
$total=0;
$count=0;

function gradePoint($g){
    switch($g){
        case "A+": return 10;
        case "A": return 9;
        case "B": return 8;
        case "C": return 6;
        default: return 0;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Result</title>

<style>
body{font-family:'Segoe UI';background:#f4f7fb;}

.card{
background:white;
padding:20px;
margin:20px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.grade{
padding:5px 10px;
border-radius:20px;
color:white;
}

.Aplus{background:#16a34a;}
.A{background:#22c55e;}
.B{background:#3b82f6;}
.C{background:#facc15;}
.F{background:#ef4444;}

</style>
</head>

<body>

<h2 style="padding:20px;">My Results</h2>

<?php while($r=$results->fetch_assoc()){ 
$class=str_replace('+','plus',$r['grade']);
$total+=gradePoint($r['grade']);
$count++;
?>

<div class="card">
<b><?= $r['title'] ?> (Sem <?= $r['semester'] ?>)</b><br>
Subject: <?= $r['subject_name'] ?><br>
Marks: <?= $r['marks'] ?><br>

<div class="grade <?= $class ?>">
<?= $r['grade'] ?>
</div>

</div>

<?php } ?>

<?php 
$cgpa = $count ? round($total/$count,2) : 0;
?>

<div class="card">
<h3>TOTAL CGPA: <?= $cgpa ?></h3>
</div>

</body>
</html>