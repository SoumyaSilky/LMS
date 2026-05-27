<?php
session_start();
include 'db_connect.php';

// SEARCH + FILTER
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : "";

// QUERY
$query = "SELECT * FROM courses WHERE 1";

if($search != ""){
    $query .= " AND title LIKE '%$search%'";
}

if($category != ""){
    $query .= " AND category='$category'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Explore Courses</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

<style>
:root{
    --bg:#f4f7fb;
    --card:#ffffff;
    --text:#1e293b;
}

body.dark{
    --bg:#0f172a;
    --card:#1e293b;
    --text:#e2e8f0;
}

body{
    background:var(--bg);
    color:var(--text);
    transition:0.3s;
}

/* HEADER */
.header{
    text-align:center;
    padding:25px;
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    color:white;
    font-size:26px;
    font-weight:600;
    position:relative;
}

/* TOGGLE */
.toggle{
    position:absolute;
    right:20px;
    top:20px;
    cursor:pointer;
    background:white;
    color:black;
    padding:6px 12px;
    border-radius:20px;
    font-size:14px;
}

/* SEARCH */
.search-box{
    text-align:center;
    margin:25px;
}

.search-box input, select{
    padding:10px 12px;
    margin:5px;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
}

.search-box button{
    padding:10px 18px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
}

.search-box button:hover{
    background:#4338ca;
}

/* GRID */
.container{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:25px;
    padding:30px;
}

/* CARD */
.card{
    background:var(--card);
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
}

/* IMAGE */
.card img{
    width:100%;
    height:160px;
    object-fit:cover;
}

/* BODY */
.card-body{
    padding:18px;
}

.card-body h3{
    font-size:18px;
    margin-bottom:8px;
}

.price{
    font-size:16px;
    font-weight:600;
    color:#22c55e;
    margin-bottom:12px;
}

/* BUTTON */
.enroll-btn{
    display:block;
    text-align:center;
    background:linear-gradient(135deg,#22c55e,#16a34a);
    color:white;
    padding:10px;
    border-radius:10px;
    text-decoration:none;
    font-weight:500;
    transition:0.3s;
}

.enroll-btn:hover{
    transform:scale(1.05);
    background:linear-gradient(135deg,#16a34a,#15803d);
}

</style>
</head>

<body>

<div class="header">
    Explore Courses
    <span class="toggle" onclick="toggleMode()">🌙</span>
</div>

<!-- SEARCH -->
<div class="search-box">
    <form method="GET">
        <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">

        <select name="category">
            <option value="">All</option>
            <option value="Web Development">Web Development</option>
            <option value="Python">Python</option>
            <option value="Java">Java</option>
            <option value="Data Science">Data Science</option>
        </select>

        <button type="submit">Search</button>
    </form>
</div>

<!-- COURSES -->
<div class="container">

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
?>


        <div class="card-body">
            <h3><?php echo $row['title']; ?></h3>
            <div class="price">₹ <?php echo $row['price']; ?></div>

            <a href="enroll.php?course_id=<?php echo $row['id']; ?>" class="enroll-btn">
                Enroll Now
            </a>
        </div>

<?php
    }
}else{
    echo "<p style='text-align:center;'>No courses found 😢</p>";
}
?>

</div>

<script>
// 🌙 DARK MODE TOGGLE
function toggleMode(){
    document.body.classList.toggle("dark");
}
</script>

</body>
</html>