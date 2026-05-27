<?php
session_start();
include 'db_connect.php';

// FETCH COURSES
$courses = $conn->query("
SELECT c.*, u.name as instructor 
FROM courses c 
LEFT JOIN users u ON c.instructor_id = u.id
ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Approve Courses</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#6366f1,#7c3aed);
    color:white;
    padding:20px;
    border-radius:12px;
    margin:20px;
    font-size:22px;
    font-weight:bold;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:20px;
    padding:20px;
}

/* CARD */
.card{
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

.card img{
    width:100%;
    height:180px;
    object-fit:cover;
}

/* CONTENT */
.content{
    padding:15px;
}

.title{
    font-size:18px;
    font-weight:bold;
}

.instructor{
    font-size:13px;
    color:#666;
}

/* PRICE */
.old{
    text-decoration:line-through;
    color:#aaa;
}

.new{
    color:#16a34a;
    font-weight:bold;
}

/* BADGE */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.pending{ background:#facc15; }
.approved{ background:#22c55e; color:white; }
.rejected{ background:#ef4444; color:white; }

/* BUTTONS */
.actions{
    margin-top:10px;
    display:flex;
    gap:10px;
}

.btn{
    flex:1;
    padding:8px;
    border:none;
    border-radius:8px;
    color:white;
    cursor:pointer;
}

.approve{ background:#22c55e; }
.reject{ background:#ef4444; }

.empty{
    text-align:center;
    padding:50px;
}
</style>
</head>

<body>

<div class="header">Approve Courses Panel</div>

<div class="grid">

<?php if($courses && $courses->num_rows > 0){ ?>

<?php while($c = $courses->fetch_assoc()){ 

$status = $c['status'] ?? 'pending';
$price = $c['price'] ?? 0;
$discount = $c['discount'] ?? 0;
$final = $price - ($price * $discount / 100);
?>

<div class="card">

<img src="uploads/<?php echo $c['image'] ?? 'default.jpg'; ?>">

<div class="content">

<div class="title"><?php echo $c['title']; ?></div>

<div class="price">
<?php if($discount > 0){ ?>
<span class="old">₹<?php echo $price; ?></span>
<span class="new">₹<?php echo $final; ?></span>
<?php } else { ?>
<span class="new">₹<?php echo $price; ?></span>
<?php } ?>
</div>

<div class="badge <?php echo $status; ?>">
<?php echo ucfirst($status); ?>
</div>

<div class="actions">

<form method="post" action="approve_action.php">
<input type="hidden" name="id" value="<?php echo $c['id']; ?>">
<button class="btn approve" name="approve">Approve</button>
</form>

<form method="post" action="approve_action.php">
<input type="hidden" name="id" value="<?php echo $c['id']; ?>">
<button class="btn reject" name="reject">Reject</button>
</form>

</div>

</div>
</div>

<?php } ?>

<?php } else { ?>

<div class="empty">No courses found</div>

<?php } ?>

</div>

</body>
</html>