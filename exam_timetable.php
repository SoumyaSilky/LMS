<?php
include("db_connect.php");

$result = $conn->query("SELECT * FROM exam_timetable ORDER BY exam_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Premium Exam Timetable</title>

<style>
body{
    font-family:'Segoe UI', sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    margin:0;
}

/* HEADER */
.header{
    text-align:center;
    padding:25px;
    font-size:26px;
    font-weight:700;
    color:#333;
}

/* CARD */
.container{
    width:90%;
    margin:auto;
}

.card{
    background:white;
    border-radius:15px;
    padding:20px;
    margin-bottom:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* FLEX */
.row{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* SUBJECT */
.subject{
    font-size:20px;
    font-weight:600;
}

/* BADGES */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    color:white;
}

.upcoming{ background:#3b82f6; }
.today{ background:#f59e0b; }
.done{ background:#10b981; }

/* COUNTDOWN */
.countdown{
    font-size:13px;
    color:#ef4444;
    margin-top:5px;
}

/* BUTTON */
.download{
    display:block;
    width:200px;
    margin:20px auto;
    padding:10px;
    text-align:center;
    background:#4f46e5;
    color:white;
    border-radius:8px;
    text-decoration:none;
}

/* EMPTY */
.empty{
    text-align:center;
    color:#888;
}
</style>

</head>

<body>

<div class="header">
     Exam Timetable
</div>

<div class="container">

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){

        $today = date("Y-m-d");
        $exam = $row['exam_date'];

        if($exam > $today){
            $status = "Upcoming";
            $class = "upcoming";
        } elseif($exam == $today){
            $status = "Today";
            $class = "today";
        } else {
            $status = "Completed";
            $class = "done";
        }
?>

<div class="card">

    <div class="row">
        <div>
            <div class="subject"><?php echo $row['subject']; ?></div>
            <div><?php echo $row['exam_date']; ?> | <?php echo $row['exam_time']; ?></div>
            <div>Duration: <?php echo $row['duration']; ?></div>
            <div>Teacher: <?php echo $row['teacher']; ?></div>

            <?php if($status == "Upcoming"){ ?>
            <div class="countdown" data-date="<?php echo $row['exam_date']; ?>"></div>
            <?php } ?>
        </div>

        <div class="badge <?php echo $class; ?>">
            <?php echo $status; ?>
        </div>
    </div>

</div>

<?php } } else { ?>
    <p class="empty">No Exams Scheduled</p>
<?php } ?>

<a href="download_exam_pdf.php" class="download">⬇ Download PDF</a>

</div>

<!-- COUNTDOWN SCRIPT -->
<script>
let timers = document.querySelectorAll(".countdown");

timers.forEach(el=>{
    let examDate = new Date(el.dataset.date).getTime();

    setInterval(()=>{
        let now = new Date().getTime();
        let diff = examDate - now;

        if(diff <= 0){
            el.innerHTML = "Exam Started";
            return;
        }

        let days = Math.floor(diff / (1000*60*60*24));
        el.innerHTML = "Starts in " + days + " days";
    },1000);
});
</script>

</body>
</html>