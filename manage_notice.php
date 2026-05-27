<?php
session_start();
include 'db_connect.php';

// ADD NOTICE
if(isset($_POST['add'])){
    $title = $_POST['title'];
    $msg = $_POST['message'];

    $conn->query("INSERT INTO notices (title, message) VALUES ('$title','$msg')");
}

// DELETE
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM notices WHERE id='$id'");
}

// SEARCH
$search = $_GET['search'] ?? "";

$query = "SELECT * FROM notices WHERE title LIKE '%$search%' ORDER BY id DESC";
$notices = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Notices</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    font-size:22px;
}

/* TOP */
.top{
    display:flex;
    justify-content:space-between;
    padding:20px;
}

/* SEARCH */
.search input{
    padding:8px;
    border-radius:8px;
    border:1px solid #ccc;
}

/* FORM */
.form{
    padding:20px;
    background:white;
    margin:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.form input, .form textarea{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

.form button{
    padding:10px;
    border:none;
    background:#4f46e5;
    color:white;
    border-radius:8px;
    cursor:pointer;
}

/* GRID */
.grid{
    padding:20px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.title{
    font-weight:bold;
}

/* STATUS */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.active{ background:#22c55e; color:white; }
.expired{ background:#ef4444; color:white; }

/* ACTIONS */
.actions{
    margin-top:10px;
}

.btn{
    padding:6px 10px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    margin-right:5px;
}

.delete{ background:#ef4444; color:white; }
.edit{ background:#3b82f6; color:white; }

</style>
</head>

<body>

<div class="header">Manage Notices</div>

<div class="top">
<form class="search">
<input type="text" name="search" placeholder="Search notice..." value="<?= $search ?>">
</form>
</div>

<!-- ADD NOTICE -->
<div class="form">
<form method="POST">
<input type="text" name="title" placeholder="Notice Title" required>
<textarea name="message" placeholder="Notice Message" required></textarea>
<button name="add">➕ Add Notice</button>
</form>
</div>

<!-- LIST -->
<div class="grid">

<?php if($notices->num_rows > 0){ ?>

<?php while($n = $notices->fetch_assoc()){ ?>

<div class="card">

<div class="title"><?= $n['title'] ?></div>

<div><?= $n['message'] ?></div>

<br>

<div class="badge <?= $n['status'] ?>">
<?= strtoupper($n['status']) ?>
</div>

<div style="font-size:12px;color:#666;">
🕒 <?= $n['created_at'] ?>
</div>

<div class="actions">

<a href="?delete=<?= $n['id'] ?>">
<button class="btn delete">Delete</button>
</a>

<a href="edit_notice.php?id=<?= $n['id'] ?>">
<button class="btn edit">Edit</button>
</a>

</div>

</div>

<?php } ?>

<?php } else { ?>

<div>No notices found</div>

<?php } ?>

</div>

</body>
</html>