<?php
session_start();
include 'db_connect.php';

// FETCH DATA
$students = $conn->query("SELECT id,name FROM users WHERE role='student'");
$courses  = $conn->query("SELECT id,title FROM courses");
$subjects = $conn->query("SELECT id,subject_name FROM subjects");

// ADD RESULT
if(isset($_POST['add'])){

    $user    = $_POST['user_id'] ?? '';
    $course  = $_POST['course_id'] ?? '';
    $subject = $_POST['subject_id'] ?? '';
    $marks   = $_POST['marks'] ?? '';
    $sem     = $_POST['semester'] ?? '';

    if($user && $course && $subject && $marks !== '' && $sem){

        // GRADE SYSTEM
        if($marks>=90) $grade="A+";
        elseif($marks>=75) $grade="A";
        elseif($marks>=60) $grade="B";
        elseif($marks>=40) $grade="C";
        else $grade="F";

        $stmt = $conn->prepare("INSERT INTO results 
        (user_id,course_id,subject_id,marks,grade,semester)
        VALUES (?,?,?,?,?,?)");

        $stmt->bind_param("iiiisi",$user,$course,$subject,$marks,$grade,$sem);
        $stmt->execute();

        $success = "✅ Result Published Successfully!";
    } else {
        $error = "❌ Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Publish Results</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    margin:20px;
    border-radius:12px;
    font-size:22px;
    font-weight:bold;
}

/* CARD */
.card{
    background:white;
    width:450px;
    margin:auto;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* INPUTS */
select, input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ddd;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}

/* ALERTS */
.success{
    background:#dcfce7;
    color:#166534;
    padding:10px;
    border-radius:8px;
}

.error{
    background:#fee2e2;
    color:#991b1b;
    padding:10px;
    border-radius:8px;
}
</style>

</head>

<body>

<div class="header">Publish Student Result</div>

<div class="card">

<?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="post">

<label>Select Student</label>
<select name="user_id" required>
<option value="">-- Select Student --</option>
<?php while($s=$students->fetch_assoc()){ ?>
<option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
<?php } ?>
</select>

<label>Select Course</label>
<select name="course_id" required>
<option value="">-- Select Course --</option>
<?php while($c=$courses->fetch_assoc()){ ?>
<option value="<?= $c['id'] ?>"><?= $c['title'] ?></option>
<?php } ?>
</select>

<label> Select Subject</label>
<select name="subject_id" required>
<option value="">-- Select Subject --</option>
<?php while($sub=$subjects->fetch_assoc()){ ?>
<option value="<?= $sub['id'] ?>"><?= $sub['subject_name'] ?></option>
<?php } ?>
</select>

<label>Marks</label>
<input type="number" name="marks" placeholder="Enter marks" required>

<label>Semester</label>
<input type="number" name="semester" placeholder="Enter semester" required>

<button name="add">Publish Result</button>

</form>

</div>

</body>
</html>