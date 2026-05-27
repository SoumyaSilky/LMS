<?php
session_start();
include 'db_connect.php';

// CHECK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// CHECK COURSE ID
if(!isset($_GET['course_id'])){
    die("❌ No course selected!");
}

$course_id = $_GET['course_id'];

// FETCH COURSE
$stmt = $conn->prepare("SELECT * FROM courses WHERE id=?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

// IF COURSE NOT FOUND
if(!$course){
    die("❌ Course not found!");
}

// SAFE VALUES
$title = $course['title'] ?? "Course";
$price = $course['price'] ?? 0;
$discount = $course['discount'] ?? 0;

// CALCULATE FINAL PRICE
$final_price = $price - ($price * $discount / 100);
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>

<style>
body{
    font-family:'Segoe UI', sans-serif;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* CARD */
.card{
    width:400px;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    text-align:center;
    animation:fade 0.5s ease;
}

@keyframes fade{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1;}
}

/* TITLE */
h2{
    margin-bottom:20px;
}

/* COURSE INFO */
.course{
    background:#f1f5ff;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
}

/* PRICE */
.price{
    font-size:22px;
    font-weight:bold;
    color:#4f46e5;
}

.old{
    text-decoration:line-through;
    color:#888;
    font-size:14px;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    border:none;
    color:white;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}
</style>

</head>

<body>

<div class="card">

    <h2>Payment</h2>

    <div class="course">
        <p><b>Course:</b> <?php echo $title; ?></p>

        <?php if($discount > 0){ ?>
            <p class="old">₹<?php echo $price; ?></p>
            <p class="price">₹<?php echo $final_price; ?> (<?php echo $discount; ?>% OFF)</p>
        <?php } else { ?>
            <p class="price">₹<?php echo $price; ?></p>
        <?php } ?>
    </div>

    <form action="pay_success.php" method="POST">
       <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
       <input type="hidden" name="amount" value="<?php echo $final_price; ?>">
       <button>Pay Now 🚀</button>
    </form>

</div>

</body>
</html>