<?php
session_start();
include("db_connect.php");

// FETCH USERS ORDER BY POINTS
$users = $conn->query("SELECT * FROM users ORDER BY points DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Leaderboard</title>

<style>

body{
    font-family:'Segoe UI';
    margin:0;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}

/* HEADER */
.header{
    background:linear-gradient(90deg,#f59e0b,#f97316);
    color:white;
    padding:25px;
    text-align:center;
    font-size:24px;
    font-weight:bold;
}

/* PODIUM */
.podium{
    display:flex;
    justify-content:center;
    align-items:flex-end;
    gap:20px;
    margin-top:30px;
}

/* CARD */
.card{
    background:white;
    padding:15px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    width:150px;
}

/* HEIGHT */
.first{height:200px;}
.second{height:160px;}
.third{height:140px;}

/* COLORS */
.gold{background:#facc15;}
.silver{background:#e5e7eb;}
.bronze{background:#f97316;color:white;}

/* AVATAR */
.avatar{
    width:60px;
    height:60px;
    border-radius:50%;
    object-fit:cover;
}

/* TABLE */
.table{
    margin:40px;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#4f46e5;
    color:white;
    padding:12px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f1f5ff;
}

/* RANK BADGE */
.rank{
    font-weight:bold;
}

</style>

</head>

<body>

<div class="header">
  Leaderboard
</div>

<?php
$top = [];
$i = 0;
while($row = $users->fetch_assoc()){
    if($i < 3){
        $top[] = $row;
    }
    $all[] = $row;
    $i++;
}
?>

<!-- 🥇 PODIUM -->
<div class="podium">

<?php if(isset($top[1])){ ?>
<div class="card second silver">
    <h3>🥈</h3>
    <img src="uploads/<?php echo $top[1]['image']; ?>" class="avatar">
    <p><?php echo $top[1]['name']; ?></p>
    <b><?php echo $top[1]['points']; ?> pts</b>
</div>
<?php } ?>

<?php if(isset($top[0])){ ?>
<div class="card first gold">
    <h3>🥇</h3>
    <img src="uploads/<?php echo $top[0]['image']; ?>" class="avatar">
    <p><?php echo $top[0]['name']; ?></p>
    <b><?php echo $top[0]['points']; ?> pts</b>
</div>
<?php } ?>

<?php if(isset($top[2])){ ?>
<div class="card third bronze">
    <h3>🥉</h3>
    <img src="uploads/<?php echo $top[2]['image']; ?>" class="avatar">
    <p><?php echo $top[2]['name']; ?></p>
    <b><?php echo $top[2]['points']; ?> pts</b>
</div>
<?php } ?>

</div>

<!-- 📊 FULL TABLE -->
<div class="table">

<table>

<tr>
    <th>Rank</th>
    <th>Name</th>
    <th>Points</th>
</tr>

<?php 
$rank = 1;
foreach($all as $u){ 
?>

<tr>
    <td class="rank">#<?php echo $rank++; ?></td>
    <td><?php echo $u['name']; ?></td>
    <td><?php echo $u['points']; ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>