<?php
session_start();
include 'db_connect.php';

$user = $_SESSION['user'];

$chat = $conn->query("SELECT * FROM chat ORDER BY id ASC");

while($c = $chat->fetch_assoc()){

    $class = ($c['sender'] == $user) ? 'sent' : 'received';

    echo "<div class='message $class'>";
    echo "<b>".$c['sender']."</b><br>";
    echo $c['message'];
    echo "<span class='time'>".$c['created_at']."</span>";
    echo "</div>";
}
?>