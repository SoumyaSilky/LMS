<?php
include 'db_connect.php';
$notices = $conn->query("SELECT * FROM notices ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notice Board</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
}

.container{
    width:80%;
    margin:30px auto;
}

.title{
    text-align:center;
    font-size:28px;
    margin-bottom:20px;
}

.notice{
    background:#fff;
    padding:15px;
    border-left:5px solid #4e73df;
    margin-bottom:15px;
    border-radius:8px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.notice h3{
    margin:0;
    color:#4e73df;
}

.notice p{
    margin:8px 0;
}

.date{
    font-size:12px;
    color:gray;
}
</style>

</head>
<body>

<div class="container">
    <div class="title">Notice Board</div>

    <?php while($n = $notices->fetch_assoc()){ ?>
        <div class="notice">
            <h3><?php echo $n['title']; ?></h3>
            <p><?php echo $n['message']; ?></p>
            <div class="date"><?php echo $n['created_at']; ?></div>
        </div>
    <?php } ?>

</div>

</body>
</html>