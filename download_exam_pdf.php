<?php
include("db_connect.php");
$data = $conn->query("SELECT * FROM exam_timetable");
?>

<!DOCTYPE html>
<html>
<head>
<title>Download PDF</title>
</head>

<body onload="window.print()">

<h2>Exam Timetable</h2>

<table border="1" width="100%" cellpadding="10">
<tr>
<th>Subject</th>
<th>Date</th>
<th>Time</th>
<th>Duration</th>
<th>Teacher</th>
</tr>

<?php while($row = $data->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['subject']; ?></td>
<td><?php echo $row['exam_date']; ?></td>
<td><?php echo $row['exam_time']; ?></td>
<td><?php echo $row['duration']; ?></td>
<td><?php echo $row['teacher']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>