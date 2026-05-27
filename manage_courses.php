<?php
session_start();
include 'db_connect.php';

// FETCH COURSES
$courses = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Courses</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#6366f1,#7c3aed);
    color:white;
    padding:20px;
    font-size:22px;
    font-weight:bold;
}

/* CONTAINER */
.container{
    padding:20px;
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-6px);
}

/* IMAGE */
.thumb{
    width:100%;
    height:160px;
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

.desc{
    font-size:13px;
    color:#666;
    margin:5px 0;
}

/* PRICE */
.price{
    margin-top:10px;
}

.old{
    text-decoration:line-through;
    color:#999;
    margin-right:8px;
}

.new{
    color:#16a34a;
    font-weight:bold;
}

/* BADGE */
.badge{
    background:#ef4444;
    color:white;
    font-size:12px;
    padding:4px 8px;
    border-radius:20px;
    margin-left:5px;
}

/* BUTTONS */
.actions{
    display:flex;
    justify-content:space-between;
    margin-top:12px;
}

.btn{
    padding:8px 12px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:12px;
}

.edit{ background:#3b82f6; color:white; }
.delete{ background:#ef4444; color:white; }

</style>
</head>

<body>

<div class="header">Manage Courses</div>

<div class="container">

<?php while($c = $courses->fetch_assoc()){ ?>

<div class="card">

    <!-- IMAGE -->
    <img src="uploads/<?php echo $c['image']; ?>" class="thumb">

    <div class="content">

        <!-- TITLE -->
        <div class="title"><?php echo $c['title']; ?></div>

        <!-- DESC -->
        <div class="desc"><?php echo substr($c['description'],0,80); ?>...</div>

        <!-- PRICE -->
        <div class="price">
            <span class="old">₹<?php echo $c['price']; ?></span>
            <span class="new">
                ₹<?php echo $c['price'] - ($c['price'] * $c['discount'] / 100); ?>
            </span>

            <?php if($c['discount'] > 0){ ?>
                <span class="badge"><?php echo $c['discount']; ?>% OFF</span>
            <?php } ?>
        </div>

        <!-- ACTIONS -->
        <div class="actions">

            <a href="edit_course.php?id=<?php echo $c['id']; ?>">
                <button class="btn edit">Edit</button>
            </a>

            <a href="delete_course.php?id=<?php echo $c['id']; ?>">
                <button class="btn delete">Delete</button>
            </a>

        </div>

    </div>

</div>

<?php } ?>

</div>

</body>
</html>