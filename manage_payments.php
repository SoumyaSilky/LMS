<?php
session_start();
include 'db_connect.php';

// FILTER
$status = $_GET['status'] ?? 'all';

// SEARCH
$search = $_GET['search'] ?? '';

// QUERY
$query = "
SELECT p.*, u.name as user_name, c.title as course_title 
FROM payments p
LEFT JOIN users u ON p.user_id = u.id
LEFT JOIN courses c ON p.course_id = c.id
WHERE 1
";

if($status != 'all'){
    $query .= " AND p.status='$status'";
}

if(!empty($search)){
    $query .= " AND (u.name LIKE '%$search%' OR c.title LIKE '%$search%')";
}

$query .= " ORDER BY p.id DESC";

$payments = $conn->query($query);

// TOTAL REVENUE
$total = $conn->query("
SELECT SUM(amount) as t FROM payments WHERE status='paid'
")->fetch_assoc()['t'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Payments</title>

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

/* TOP BAR */
.topbar{
    display:flex;
    justify-content:space-between;
    padding:20px;
}

/* FILTER */
.filters a{
    margin-right:10px;
    padding:8px 15px;
    background:#e5e7eb;
    border-radius:20px;
    text-decoration:none;
}

.filters a.active{
    background:#4f46e5;
    color:white;
}

/* SEARCH */
.search input{
    padding:8px;
    border-radius:8px;
    border:1px solid #ccc;
}

/* TOTAL */
.total{
    padding:0 20px;
    font-size:18px;
    font-weight:bold;
    color:#16a34a;
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
    padding:20px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

/* TITLE */
.title{
    font-weight:bold;
}

/* BADGE */
.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

.paid{ background:#22c55e; color:white; }
.pending{ background:#facc15; }
.failed{ background:#ef4444; color:white; }

/* AMOUNT */
.amount{
    font-size:20px;
    color:#4f46e5;
    margin-top:10px;
}

</style>
</head>

<body>

<div class="header">Manage Payments</div>

<!-- TOP BAR -->
<div class="topbar">

    <div class="filters">
        <a href="?status=all" class="<?= $status=='all'?'active':'' ?>">All</a>
        <a href="?status=paid" class="<?= $status=='paid'?'active':'' ?>">Paid</a>
        <a href="?status=pending" class="<?= $status=='pending'?'active':'' ?>">Pending</a>
        <a href="?status=failed" class="<?= $status=='failed'?'active':'' ?>">Failed</a>
    </div>

    <form class="search">
        <input type="text" name="search" placeholder="Search..." value="<?= $search ?>">
    </form>

</div>

<div class="total">Total Revenue: ₹<?= $total ?></div>

<!-- PAYMENTS -->
<div class="grid">

<?php if($payments->num_rows > 0){ ?>

<?php while($p = $payments->fetch_assoc()){ ?>

<div class="card">

<div class="title">
👤 <?= $p['user_name'] ?? 'Unknown' ?>
</div>

<div> <?= $p['course_title'] ?? 'Course' ?></div>

<div class="amount">₹<?= $p['amount'] ?></div>

<div class="badge <?= $p['status'] ?>">
<?= strtoupper($p['status']) ?>
</div>

<div style="margin-top:10px;font-size:12px;color:#666;">
🕒 <?= $p['created_at'] ?? 'N/A' ?>
</div>

</div>

<?php } ?>

<?php } else { ?>

<div>No payments found</div>

<?php } ?>

</div>

</body>
</html>