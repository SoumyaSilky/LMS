<?php
include 'db_connect.php';

if(isset($_POST['id'])){

    $id = $_POST['id'];

    if(isset($_POST['approve'])){
        $conn->query("UPDATE courses SET status='approved' WHERE id='$id'");
    }

    if(isset($_POST['reject'])){
        $conn->query("UPDATE courses SET status='rejected' WHERE id='$id'");
    }

}

header("Location: approve_courses.php");
?>