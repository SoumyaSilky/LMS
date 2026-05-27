<?php
session_start();
include 'db_connect.php';

// FETCH USERS & COURSES
$users = $conn->query("SELECT id,name FROM users WHERE role='student'");
$courses = $conn->query("SELECT id,title FROM courses");

// ADD RESULT
if(isset($_POST['add'])){
    $user_id = $_POST['user_id'];
    $course_id = $_POST['course_id'];
    $marks = $_POST['marks'];

    // AUTO GRADE
    if($marks >= 90) $grade = "A+";
    elseif($marks >= 75) $grade = "A";
    elseif($marks >= 60) $grade = "B";
    elseif($marks >= 40) $grade = "C";
    else $grade = "F";

    $conn->query("INSERT INTO results (user_id, course_id, marks, grade) 
    VALUES ('$user_id','$course_id','$marks','$grade')");
}

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM results WHERE id='$id'");
}

// SEARCH
$search = $_GET['search'] ?? "";

$query = "
SELECT r.*, u.name as student, c.title as course
FROM results r
LEFT JOIN users u ON r.user_id = u.id
LEFT JOIN courses c ON r.course_id = c.id
WHERE u.name LIKE '%$search%'
ORDER BY r.id DESC
";

$results = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Publish Results</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    padding:20px;
    font-size:22px;
}

/* FORM */
.form{
    background:white;
    padding:20px;
    margin:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.form select, .form input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

.form button{
    padding:10px;
    background:#22c55e;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

/* SEARCH */
.search{
    padding:0 20px;
}

/* GRID */
.grid{
    padding:20px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* BADGE */
.grade{
    padding:5px 10px;
    border-radius:20px;
    color:white;
    font-size:12px;
}

.Aplus{ background:#16a34a; }
.A{ background:#22c55e; }
.B{ background:#3b82f6; }
.C{ background:#facc15; }
.F{ background:#ef4444; }

/* BUTTON */
.btn{
    margin-top:10px;
    padding:6px 10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.delete{ background:#ef4444; color:white; }

</style>
</head>

<body>

<div class="header">Publish Student Results</div>

<!-- ADD FORM -->
<div class="form">
<form method="POST">

<select name="user_id" required>
<option value="">Select Student</option>
<?php while($u = $users->fetch_assoc()){ ?>
<option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
<?php } ?>
</select>

<select name="course_id" required>
<option value="">Select Course</option>
<?php while($c = $courses->fetch_assoc()){ ?>
<option value="<?= $c['id'] ?>"><?= $c['title'] ?></option>
<?php } ?>
</select>

<input type="number" name="marks" placeholder="Enter Marks (0-100)" required>

<button name="add">Publish Result</button>

</form>
</div>

<!-- SEARCH -->
<div class="search">
<form>
<input type="text" name="search" placeholder="Search student..." value="<?= $search ?>">
</form>
</div>

<!-- RESULTS -->
<div class="grid">

<?php if($results->num_rows > 0){ ?>

<?php while($r = $results->fetch_assoc()){ 

$class = str_replace('+','plus',$r['grade']);
?>

<div class="card">

<div><b>👤 <?= $r['student'] ?></b></div>
<div>📚 <?= $r['course'] ?></div>

<div>Marks: <b><?= $r['marks'] ?></b></div>

<div class="grade <?= $class ?>">
<?= $r['grade'] ?>
</div>

<div style="font-size:12px;color:#666;">
🕒 <?= $r['created_at'] ?>
</div>

<a href="?delete=<?= $r['id'] ?>">
<button class="btn delete">Delete</button>
</a>

</div>

<?php } ?>

<?php } else { ?>

<div>No results found </div>

<?php } ?>

</div>

</body>
</html>