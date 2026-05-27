<?php
session_start();
include("db_connect.php");

// CHECK LOGIN
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// GET USER DATA
$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$u = $result->fetch_assoc();

$user_id = $u['id'];

// FETCH RESOURCES (FIXED)
$resources = $conn->query("SELECT * FROM resources ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>
<title>Resources</title>

<style>

/* ===== BODY ===== */
body{
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#eef2ff,#f8fafc);
    margin:0;
}

/* HEADER */
.header{
    background: linear-gradient(90deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    text-align:center;
    font-size:22px;
    font-weight:bold;
}

/* CONTAINER */
.container{
    padding:30px;
    display:grid;
    grid-template-columns: repeat(auto-fill,minmax(280px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* TITLE */
.card h3{
    margin-bottom:10px;
}

/* DESC */
.card p{
    font-size:14px;
    color:#555;
}

/* BUTTON */
.btn{
    display:inline-block;
    margin-top:10px;
    padding:10px 15px;
    background:#4f46e5;
    color:white;
    text-decoration:none;
    border-radius:8px;
    transition:0.3s;
}

.btn:hover{
    background:#3730a3;
}

/* EMPTY */
.empty{
    text-align:center;
    color:red;
    font-weight:bold;
    margin-top:50px;
}

</style>

</head>

<body>

<div class="header">
  Course Resources
</div>

<div class="container">

<?php if($resources && $resources->num_rows > 0){ ?>

    <?php while($r = $resources->fetch_assoc()){ ?>

        <div class="card">
            <h3><?php echo $r['title'] ?? 'No Title'; ?></h3>
            <p><?php echo $r['description'] ?? 'No description'; ?></p>

            <?php if(!empty($r['file'])){ ?>
                <a class="btn" href="uploads/resources/<?php echo $r['file']; ?>" download>
                    ⬇ Download Notes
                </a>
            <?php } else { ?>
                <p style="color:red;">❌ File not available</p>
            <?php } ?>
        </div>

    <?php } ?>

<?php } else { ?>

    <div class="empty">❌ Notes not available</div>

<?php } ?>

</div>

</body>
</html>