<?php
include("db_connect.php");

$result = $conn->query("SELECT * FROM exam_timetable ORDER BY exam_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Exam Timetable</title>

<style>
body{
    font-family:'Segoe UI', sans-serif;
    background:#f4f7fb;
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#ff6a00,#ee0979);
    color:white;
    text-align:center;
    padding:20px;
    font-size:24px;
    font-weight:600;
}

/* CONTAINER */
.container{
    padding:30px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

th{
    background:#ff6a00;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9fafb;
}

/* BADGE */
.date-badge{
    background:#4e73df;
    color:white;
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

/* EMPTY */
.empty{
    text-align:center;
    padding:20px;
    color:#888;
}
</style>

</head>

<body>

<div class="header">
     Exam Timetable
</div>

<div class="container">

<table>
<tr>
    <th>Subject</th>
    <th>Date</th>
    <th>Time</th>
    <th>Duration</th>
    <th>Teacher</th>
</tr>

<?php if($result->num_rows > 0){ ?>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['subject']; ?></td>
        <td><span class="date-badge"><?php echo $row['exam_date']; ?></span></td>
        <td><?php echo $row['exam_time']; ?></td>
        <td><?php echo $row['duration']; ?></td>
        <td><?php echo $row['teacher']; ?></td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="5" class="empty">No Exam Scheduled</td>
    </tr>
<?php } ?>

</table>

</div>

</body>
</html>