<?php
session_start();
include 'db_connect.php';

// CHECK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];

// GET USER
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$user_id = $user['id'];

// FETCH ATTENDANCE
$data = $conn->query("SELECT * FROM attendance WHERE user_id='$user_id' ORDER BY date DESC");

// CALCULATE
$total = 0;
$present = 0;

$records = [];

if($data && $data->num_rows > 0){
    while($row = $data->fetch_assoc()){
        $records[] = $row;
        $total++;
        if($row['status'] == 'Present'){
            $present++;
        }
    }
}

$percentage = ($total > 0) ? round(($present/$total)*100) : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Attendance</title>

<style>

body{
    font-family: 'Segoe UI', sans-serif;
    margin:0;
    background:#f4f7fb;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#6366f1,#9333ea);
    color:white;
    padding:25px;
    border-radius:0 0 20px 20px;
    text-align:center;
}

/* CONTAINER */
.container{
    padding:20px;
}

/* STATS */
.stats{
    display:flex;
    gap:20px;
    margin-top:-40px;
    flex-wrap:wrap;
}

.card{
    background:white;
    padding:20px;
    border-radius:15px;
    flex:1;
    min-width:200px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* PROGRESS */
.progress{
    background:#eee;
    border-radius:20px;
    overflow:hidden;
    margin-top:10px;
}

.progress-bar{
    height:10px;
    background:linear-gradient(to right,#22c55e,#4ade80);
}

/* TABLE */
.table{
    margin-top:30px;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.table table{
    width:100%;
    border-collapse:collapse;
}

.table th{
    background:#4f46e5;
    color:white;
    padding:12px;
}

.table td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}

/* BADGES */
.present{
    background:#22c55e;
    color:white;
    padding:5px 10px;
    border-radius:20px;
}

.absent{
    background:#ef4444;
    color:white;
    padding:5px 10px;
    border-radius:20px;
}

/* EMPTY */
.empty{
    text-align:center;
    padding:20px;
    color:gray;
}

</style>

</head>

<body>

<div class="header">
    <h2> Attendance Dashboard</h2>
    <p><?php echo $user['name']; ?></p>
</div>

<div class="container">

<!-- STATS -->
<div class="stats">

    <div class="card">
        <h3>Total Days</h3>
        <h2><?php echo $total; ?></h2>
    </div>

    <div class="card">
        <h3>Present</h3>
        <h2><?php echo $present; ?></h2>
    </div>

    <div class="card">
        <h3>Attendance %</h3>
        <h2><?php echo $percentage; ?>%</h2>

        <div class="progress">
            <div class="progress-bar" style="width:<?php echo $percentage; ?>%"></div>
        </div>
    </div>

</div>

<!-- TABLE -->
<div class="table">

<table>

<tr>
    <th>Date</th>
    <th>Status</th>
</tr>

<?php if(count($records) > 0){ ?>

    <?php foreach($records as $row){ ?>
    <tr>
        <td><?php echo $row['date']; ?></td>
        <td>
            <?php if($row['status'] == "Present"){ ?>
                <span class="present">Present</span>
            <?php } else { ?>
                <span class="absent">Absent</span>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>

<?php } else { ?>

<tr>
    <td colspan="2" class="empty">No attendance records</td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>