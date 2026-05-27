<?php
session_start();
include 'db_connect.php';

$id = $_GET['id'];
$course = $conn->query("SELECT * FROM courses WHERE id='$id'")->fetch_assoc();

if(isset($_POST['update'])){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];

    // IMAGE UPLOAD
    if(!empty($_FILES['image']['name'])){
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);

        $conn->query("UPDATE courses SET 
            title='$title',
            description='$description',
            price='$price',
            discount='$discount',
            image='$image'
            WHERE id='$id'
        ");
    } else {
        $conn->query("UPDATE courses SET 
            title='$title',
            description='$description',
            price='$price',
            discount='$discount'
            WHERE id='$id'
        ");
    }

    header("Location: manage_courses.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Course</title>

<style>
body{
    font-family:'Segoe UI';
    background:#f4f7fb;
}

/* CONTAINER */
.container{
    max-width:900px;
    margin:40px auto;
    background:white;
    border-radius:15px;
    padding:25px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* HEADER */
.header{
    font-size:22px;
    font-weight:bold;
    margin-bottom:20px;
    color:#4f46e5;
}

/* FORM */
.form-group{
    margin-bottom:15px;
}

label{
    font-size:14px;
    font-weight:600;
}

input, textarea{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #ddd;
    margin-top:5px;
}

/* IMAGE PREVIEW */
.preview{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:10px;
}

/* PRICE BOX */
.price-box{
    display:flex;
    gap:10px;
}

/* BUTTON */
.btn{
    background:linear-gradient(135deg,#6366f1,#7c3aed);
    color:white;
    border:none;
    padding:12px;
    border-radius:10px;
    cursor:pointer;
    width:100%;
    font-size:16px;
    margin-top:15px;
}

.btn:hover{
    opacity:0.9;
}

/* LIVE PRICE */
.live-price{
    margin-top:10px;
    font-size:14px;
}

.old{
    text-decoration:line-through;
    color:#999;
}

.new{
    color:#16a34a;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="container">

<div class="header">Edit Course</div>

<form method="post" enctype="multipart/form-data">

    <!-- IMAGE -->
    <div class="form-group">
        <img src="uploads/<?php echo $course['image']; ?>" class="preview">
        <input type="file" name="image">
    </div>

    <!-- TITLE -->
    <div class="form-group">
        <label>Course Title</label>
        <input type="text" name="title" value="<?php echo $course['title']; ?>" required>
    </div>

    <!-- DESCRIPTION -->
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="4"><?php echo $course['description']; ?></textarea>
    </div>

    <!-- PRICE -->
    <div class="form-group">
        <label>Price & Discount</label>

        <div class="price-box">
            <input type="number" id="price" name="price" value="<?php echo $course['price']; ?>" placeholder="Price">
            <input type="number" id="discount" name="discount" value="<?php echo $course['discount']; ?>" placeholder="Discount %">
        </div>

        <!-- LIVE PRICE -->
        <div class="live-price">
            <span class="old" id="oldPrice"></span>
            <span class="new" id="newPrice"></span>
        </div>
    </div>

    <button class="btn" name="update">Update Course</button>

</form>

</div>

<script>
// LIVE PRICE CALCULATION
function updatePrice(){
    let price = document.getElementById("price").value || 0;
    let discount = document.getElementById("discount").value || 0;

    let final = price - (price * discount / 100);

    document.getElementById("oldPrice").innerText = "₹"+price;
    document.getElementById("newPrice").innerText = " → ₹"+final.toFixed(0);
}

document.getElementById("price").addEventListener("input", updatePrice);
document.getElementById("discount").addEventListener("input", updatePrice);

updatePrice();
</script>

</body>
</html>