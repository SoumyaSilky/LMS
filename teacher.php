<?php
session_start();
include("db_connect.php");

// FETCH SUBMISSIONS (IMPORTANT FIX)
$res = $conn->query("
SELECT submissions.*, users.name, assignments.title 
FROM submissions
JOIN users ON submissions.user_id = users.id
JOIN assignments ON submissions.assignment_id = assignments.id
ORDER BY submissions.id DESC
");

// UPDATE MARKS
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $marks = $_POST['marks'];
    $feedback = $_POST['feedback'];

    $conn->query("
        UPDATE submissions 
        SET marks='$marks', feedback='$feedback', status='Checked'
        WHERE id='$id'
    ");

    header("Location: teacher.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Check Assignments</title>

<style>

/* BODY */
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(90deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    text-align:center;
    font-size:22px;
}

/* TABLE */
.container{
    padding:20px;
}

table{
    width:100%;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
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

/* INPUT */
input, textarea{
    width:90%;
    padding:6px;
    border-radius:6px;
    border:1px solid #ccc;
}

/* BUTTON */
button{
    background:#22c55e;
    color:white;
    border:none;
    padding:6px 10px;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#16a34a;
}

/* STATUS */
.status{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.submitted{
    background:#facc15;
}

.checked{
    background:#22c55e;
    color:white;
}

/* FILE */
.file{
    color:#4f46e5;
    text-decoration:none;
}

</style>

</head>

<body>

<div class="header">
📚 Check Student Assignments
</div>

<div class="container">

<table>

<tr>
    <th>Student</th>
    <th>Assignment</th>
    <th>File</th>
    <th>Status</th>
    <th>Marks</th>
    <th>Feedback</th>
    <th>Action</th>
</tr>

<?php while($row = $res->fetch_assoc()){ ?>

<tr>
<form method="POST">

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['title']; ?></td>

<td>
    <a class="file" href="uploads/<?php echo $row['file']; ?>" target="_blank">
        View
    </a>
</td>

<td>
    <span class="status <?php echo strtolower($row['status']); ?>">
        <?php echo $row['status']; ?>
    </span>
</td>

<td>
    <input type="number" name="marks" value="<?php echo $row['marks']; ?>">
</td>

<td>
    <textarea name="feedback"><?php echo $row['feedback']; ?></textarea>
</td>

<td>
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <button name="update">Update</button>
</td>

</form>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>