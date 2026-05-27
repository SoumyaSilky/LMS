<?php
session_start();
include 'db_connect.php';

// FETCH USERS
$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $users = $conn->query("SELECT * FROM users WHERE name LIKE '%$search%' OR email LIKE '%$search%'");
} else {
    $users = $conn->query("SELECT * FROM users");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>

<style>
body{
    font-family: 'Segoe UI';
    background:#f4f7fb;
    margin:0;
}

/* HEADER */
.header{
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    padding:20px;
    font-size:22px;
    font-weight:bold;
}

/* SEARCH */
.search-box{
    padding:20px;
    display:flex;
    gap:10px;
}

.search-box input{
    padding:10px;
    width:250px;
    border-radius:8px;
    border:1px solid #ccc;
}

.search-box button{
    padding:10px 15px;
    border:none;
    background:#4f46e5;
    color:white;
    border-radius:8px;
    cursor:pointer;
}

/* GRID */
.container{
    padding:20px;
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(250px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    border-radius:12px;
    padding:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    text-align:center;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* IMAGE */
.avatar{
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:10px;
}

/* NAME */
.name{
    font-size:18px;
    font-weight:bold;
}

/* EMAIL */
.email{
    font-size:13px;
    color:#777;
}

/* ROLE BADGE */
.badge{
    display:inline-block;
    padding:5px 10px;
    border-radius:20px;
    margin-top:5px;
    font-size:12px;
}

.student{ background:#e0f2fe; color:#0369a1; }
.teacher{ background:#dcfce7; color:#166534; }
.admin{ background:#fee2e2; color:#991b1b; }

/* BUTTONS */
.actions{
    margin-top:10px;
}

.btn{
    padding:8px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    margin:3px;
    font-size:12px;
}

.delete{
    background:#ef4444;
    color:white;
}

.promote{
    background:#22c55e;
    color:white;
}
</style>
</head>

<body>

<div class="header">Manage Users</div>

<!-- SEARCH -->
<form class="search-box" method="GET">
    <input type="text" name="search" placeholder="Search users..." value="<?php echo $search; ?>">
    <button type="submit">Search</button>
</form>

<!-- USERS -->
<div class="container">

<?php while($u = $users->fetch_assoc()){ ?>

<div class="card">

    <img src="uploads/<?php echo $u['image']; ?>" class="avatar">

    <div class="name"><?php echo $u['name']; ?></div>
    <div class="email"><?php echo $u['email']; ?></div>

    <!-- ROLE -->
    <div class="badge <?php echo $u['role']; ?>">
        <?php echo strtoupper($u['role']); ?>
    </div>

    <!-- ACTIONS -->
    <div class="actions">

        <!-- DELETE -->
        <a href="delete_user.php?id=<?php echo $u['id']; ?>">
            <button class="btn delete">Delete</button>
        </a>

        <!-- PROMOTE -->
        <?php if($u['role'] == 'student'){ ?>
        <a href="promote.php?id=<?php echo $u['id']; ?>">
            <button class="btn promote">Make Teacher</button>
        </a>
        <?php } ?>

    </div>

</div>

<?php } ?>

</div>

</body>
</html>