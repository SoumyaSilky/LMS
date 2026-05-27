<?php
session_start();
include("db_connect.php");

// FETCH COURSES
$courses = $conn->query("SELECT * FROM courses");

// FETCH STUDENTS
$students = $conn->query("SELECT * FROM users WHERE role='student'");

// SAVE ATTENDANCE
if(isset($_POST['save'])){
    $course_id = $_POST['course_id'];
    $date = $_POST['date'];

    foreach($_POST['attendance'] as $user_id => $status){
        $conn->query("
            INSERT INTO attendance(user_id, course_id, date, status)
            VALUES('$user_id','$course_id','$date','$status')
        ");
    }

    $msg = "✅ Attendance Saved!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Attendance</title>

<style>

body{
    font-family:'Segoe UI';
    margin:0;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}

/* HEADER */
.header{
    background:linear-gradient(90deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    text-align:center;
    font-size:22px;
}

/* CONTAINER */
.container{
    width:90%;
    margin:30px auto;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

/* FORM */
.form{
    display:flex;
    gap:20px;
    margin-bottom:20px;
}

select, input[type="date"]{
    padding:10px;
    border-radius:10px;
    border:1px solid #ccc;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#4f46e5;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    border-bottom:1px solid #ddd;
}

/* RADIO */
label{
    margin-right:10px;
}

/* BUTTON */
button{
    padding:10px 20px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#3730a3;
}

/* MESSAGE */
.msg{
    color:green;
    margin-bottom:10px;
}

/* STATUS COLORS */
.present{
    color:green;
    font-weight:bold;
}
.absent{
    color:red;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="header">
    Teacher Attendance Panel
</div>

<div class="container">

<div class="card">

<?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

<form method="POST">

<div class="form">

<select name="course_id" required>
<option value="">Select Course</option>
<?php while($c = $courses->fetch_assoc()){ ?>
<option value="<?php echo $c['id']; ?>">
    <?php echo $c['title']; ?>
</option>
<?php } ?>
</select>

<input type="date" name="date" required>

<button name="save">Save Attendance</button>

</div>

<table>
<tr>
    <th>Student Name</th>
    <th>Attendance</th>
</tr>

<?php while($s = $students->fetch_assoc()){ ?>
<tr>
    <td><?php echo $s['name']; ?></td>

    <td>
        <label>
            <input type="radio" name="attendance[<?php echo $s['id']; ?>]" value="Present" required> Present
        </label>

        <label>
            <input type="radio" name="attendance[<?php echo $s['id']; ?>]" value="Absent"> Absent
        </label>
    </td>
</tr>
<?php } ?>

</table>

</form>

</div>

</div>

</body>
</html>