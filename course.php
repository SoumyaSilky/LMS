<?php
session_start();
include 'db_connect.php';

// CHECK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// GET USER
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();
$user_id = $u['id'];

// GET COURSE ID
$course_id = $_GET['id'] ?? 0;

// GET COURSE
$course = $conn->query("SELECT * FROM courses WHERE id='$course_id'")->fetch_assoc();

if(!$course){
    die("Course not found");
}

// SAFE instructor_id
$instructor_id = isset($course['instructor_id']) ? $course['instructor_id'] : 1;

// GET INSTRUCTOR
$inst = $conn->query("SELECT * FROM users WHERE id='$instructor_id'")->fetch_assoc();

// GET LESSONS
$lessons = $conn->query("SELECT * FROM lessons WHERE course_id='$course_id'");

// GET FIRST VIDEO
$firstLesson = $conn->query("SELECT * FROM lessons WHERE course_id='$course_id' LIMIT 1")->fetch_assoc();

// GET RESOURCES
$resources = $conn->query("SELECT * FROM resources WHERE course_id='$course_id'");

// PROGRESS
$p = $conn->query("
    SELECT progress FROM progress 
    WHERE user_id='$user_id' AND course_id='$course_id'
")->fetch_assoc();

$progress = $p ? $p['progress'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $course['title']; ?></title>

<style>
body{
    font-family:'Segoe UI';
    margin:0;
    background:#f5f7fb;
}

/* HEADER */
.header{
    background:linear-gradient(90deg,#4f46e5,#9333ea);
    color:white;
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header img{
    width:80px;
    border-radius:10px;
}

/* MAIN */
.container{
    display:flex;
    padding:20px;
    gap:20px;
}

/* LEFT */
.left{
    flex:2;
}

/* VIDEO */
video{
    width:100%;
    border-radius:10px;
}

/* LESSON LIST */
.lessons{
    background:white;
    padding:15px;
    margin-top:15px;
    border-radius:10px;
}

.lessons li{
    list-style:none;
    padding:10px;
    cursor:pointer;
    border-bottom:1px solid #eee;
}

.lessons li:hover{
    background:#f1f5ff;
}

/* RIGHT */
.right{
    flex:1;
}

/* CARD */
.card{
    background:white;
    padding:15px;
    border-radius:10px;
    margin-bottom:15px;
}

/* BUTTON */
.btn{
    display:block;
    padding:10px;
    text-align:center;
    background:#4f46e5;
    color:white;
    border-radius:8px;
    text-decoration:none;
    margin-top:10px;
}

/* BADGE */
.badge{
    background:red;
    color:white;
    padding:5px 10px;
    border-radius:5px;
    font-size:12px;
}

/* PROGRESS */
.progress{
    height:10px;
    background:#ddd;
    border-radius:5px;
    margin-top:10px;
}

.progress-bar{
    height:10px;
    background:green;
    border-radius:5px;
}
</style>

</head>

<body>

<!-- HEADER -->
<div class="header">
    <div>
        <h2><?php echo $course['title']; ?></h2>
        <p><?php echo $course['description']; ?></p>
    </div>

    <img src="uploads/<?php echo $course['image']; ?>">
</div>

<div class="container">

<!-- LEFT -->
<div class="left">

<!-- VIDEO -->
<iframe id="player"
    width="100%"
    height="400"
    src=""
    frameborder="0"
    allowfullscreen>
</iframe>

<!-- LESSONS -->
<div class="lessons">
    <h3>Lessons</h3>
    <ul>
        <?php while($l = $lessons->fetch_assoc()){ ?>
            <div class="lesson"
             onclick="playVideo('<?php echo $l['youtube_link']; ?>')">
            ▶ <?php echo $l['title']; ?>
            </div>
        <?php } ?>
    </ul>
</div>

</div>

<!-- RIGHT -->
<div class="right">

<!-- PRICE -->
<div class="card">
    <h3>₹<?php echo $course['price']; ?>
    <?php if($course['discount'] > 0){ ?>
        <span class="badge"><?php echo $course['discount']; ?>% OFF</span>
    <?php } ?>
    </h3>
</div>

<?php if($course['price'] > 0){ ?>
    <a class="btn" href="payment.php?course_id=<?php echo $course['id']; ?>">
        💳 Buy Now
    </a>
<?php } else { ?>
    <a class="btn" href="enroll.php?id=<?php echo $course['id']; ?>">
        Enroll Free
    </a>
<?php } ?>

<!-- PROGRESS -->
<div class="card">
    <h3>Progress</h3>
    <div class="progress">
        <div class="progress-bar" style="width:<?php echo $progress; ?>%"></div>
    </div>
    <p><?php echo $progress; ?>% Completed</p>
</div>

<!-- RESOURCES -->
<div class="card">
    <h3>Resources</h3>
    <?php while($r = $resources->fetch_assoc()){ ?>
        <a class="btn" href="uploads/<?php echo $r['file']; ?>" download>
            Download <?php echo $r['file']; ?>
        </a>
    <?php } ?>
</div>

<div class="card-btns">

    <!-- ✅ TAKE QUIZ BUTTON -->
    <a class="btn" href="quiz.php?course_id=<?php echo $course['id']; ?>">
        Take Quiz
    </a>

</div>

</div>

</div>

<script>
function playVideo(link){

    // Convert YouTube link → embed
    let videoId = link.split("v=")[1];
    let embed = "https://www.youtube.com/embed/" + videoId;

    document.getElementById("player").src = embed;
}
</script>

</body>
</html>