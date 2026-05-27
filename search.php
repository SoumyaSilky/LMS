<?php
session_start();
include("db_connect.php");

// FETCH COURSES
$courses = $conn->query("SELECT * FROM courses");

// FETCH STUDENTS
$students = $conn->query("SELECT * FROM users WHERE role='student'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Search</title>

<style>

/* BODY */
body{
    font-family:'Segoe UI';
    margin:0;
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
}

/* HEADER */
.header{
    background:linear-gradient(90deg,#4f46e5,#7c3aed);
    color:white;
    padding: 25px;
    text-align:center;
    font-size:25px;
}

/* SEARCH BOX */
.search-box{
    margin:20px auto;
    width:60%;
    position:relative;
}

.search-box input{
    width:100%;
    padding:12px 15px;
    border-radius:30px;
    border:none;
    outline:none;
    font-size:16px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* CONTAINER */
.container{
    padding:20px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    border-radius:15px;
    padding:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* IMAGE */
.card img{
    width:100%;
    height:150px;
    object-fit:cover;
    border-radius:10px;
}

/* TITLE */
.card h3{
    margin:10px 0;
}

/* BUTTON */
.btn{
    display:inline-block;
    margin-top:10px;
    padding:8px 12px;
    background:#4f46e5;
    color:white;
    border-radius:8px;
    text-decoration:none;
}

.empty{
    text-align:center;
    color:gray;
}

</style>

</head>

<body>

<div class="header">
     Search Courses
</div>

<div class="search-box">
    <input type="text" id="search" placeholder="Search courses">
</div>

<div class="container">

<h2>Courses</h2>
<div class="grid" id="courseList">

<?php if($courses && $courses->num_rows > 0){ ?>
    <?php while($c = $courses->fetch_assoc()){ ?>
        <div class="card course">
            <img src="uploads/<?php echo $c['image']; ?>" onerror="this.src='https://via.placeholder.com/300'">
            <h3><?php echo $c['title']; ?></h3>
            <p><?php echo substr($c['description'],0,80); ?>...</p>
            <a class="btn" href="course.php?id=<?php echo $c['id']; ?>">View</a>
        </div>
    <?php } ?>
<?php } else { ?>
    <p class="empty">No courses found</p>
<?php } ?>

</div>

</div>

</div>

<script>

// LIVE SEARCH
document.getElementById("search").addEventListener("keyup", function(){
    let value = this.value.toLowerCase();

    document.querySelectorAll(".course, .student").forEach(card=>{
        let text = card.innerText.toLowerCase();
        card.style.display = text.includes(value) ? "block" : "none";
    });
});

</script>

</body>
</html>