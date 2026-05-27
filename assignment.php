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

// GET ASSIGNMENTS
$assignments = $conn->query("SELECT * FROM assignments");
?>

<!DOCTYPE html>
<html>
<head>
<title>Assignments</title>

<style>

body{
    font-family:'Segoe UI';
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(90deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    font-size:22px;
    font-weight:bold;
    text-align:center;
}

/* GRID */
.container{
    padding:30px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:rgba(255,255,255,0.8);
    backdrop-filter:blur(10px);
    border-radius:20px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* TITLE */
.card h3{
    margin:0;
}

/* DEADLINE */
.deadline{
    font-size:14px;
    margin:10px 0;
}

.deadline.red{
    color:red;
    font-weight:bold;
}

/* STATUS */
.status{
    padding:5px 10px;
    border-radius:20px;
    display:inline-block;
    font-size:12px;
    margin-bottom:10px;
}

.pending{background:#facc15;}
.submitted{background:#22c55e;color:white;}
.checked{background:#3b82f6;color:white;}

/* BUTTON */
.btn{
    padding:10px 15px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    background:#4f46e5;
    color:white;
    transition:0.3s;
}

.btn:hover{
    background:#3730a3;
}

/* FILE INPUT */
input[type="file"]{
    margin-top:10px;
}

/* MARKS */
.marks{
    margin-top:10px;
    font-weight:bold;
}

.feedback{
    font-size:14px;
    color:#555;
}

</style>
</head>

<body>

<div class="header">Assignments</div>

<div class="container">

<?php while($a = $assignments->fetch_assoc()){ 

    $aid = $a['id'];

    $sub = $conn->query("
        SELECT * FROM submissions 
        WHERE assignment_id='$aid' AND user_id='$user_id'
    ")->fetch_assoc();

    $today = date("Y-m-d");
    $deadlineClass = ($today > $a['deadline']) ? "deadline red" : "deadline";

    $status = $sub ? strtolower($sub['status']) : "pending";
?>

<div class="card">

    <h3><?php echo $a['title']; ?></h3>
    <p><?php echo $a['description']; ?></p>

    <div class="<?php echo $deadlineClass; ?>">
         Deadline: <?php echo $a['deadline']; ?>
    </div>

    <div class="status <?php echo $status; ?>">
        <?php echo ucfirst($status); ?>
    </div>

    <?php if(!$sub){ ?>

        <!-- SUBMIT FORM -->
        <form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="assignment_id" value="<?php echo $aid; ?>">
            <input type="file" name="file" required>
            <br><br>
            <button class="btn">Submit Assignment</button>
        </form>

    <?php } else { ?>

        <p>Submitted File: <?php echo $sub['file']; ?></p>

        <div class="marks">Marks: <?php echo $sub['marks']; ?></div>
        <div class="feedback">Feedback: <?php echo $sub['feedback']; ?></div>

    <?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>