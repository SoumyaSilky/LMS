<?php
include 'db_connect.php';
$data = $conn->query("SELECT * FROM timetable");
?>

<!DOCTYPE html>
<html>
<head>
<title>Timetable</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
}

.container{
    width:80%;
    margin:30px auto;
}

.title{
    text-align:center;
    font-size:28px;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

th{
    background:#1cc88a;
    color:white;
    padding:12px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9fafb;
}
</style>

</head>
<body>

<div class="container">

<div class="title">Class Timetable From Monday To Saturday</div>

<table>
<tr>
<th>Day</th>
<th>Subject</th>
<th>Time</th>
<th>Teacher</th>
</tr>

<?php while($t = $data->fetch_assoc()){ ?>

<tr>
<td><?php echo $t['day']; ?></td>
<td><?php echo $t['subject']; ?></td>
<td><?php echo $t['time']; ?></td>
<td><?php echo $t['teacher']; ?></td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>