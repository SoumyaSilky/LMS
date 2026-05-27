<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>
<link rel="stylesheet" href="css/style.css">

<style>
.chat-container{
    max-width:700px;
    margin:auto;
    background:#fff;
    border-radius:12px;
    padding:15px;
    height:80vh;
    display:flex;
    flex-direction:column;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

.chat-box{
    flex:1;
    overflow-y:auto;
    padding:10px;
}

.message{
    margin:10px 0;
    max-width:70%;
    padding:10px;
    border-radius:12px;
    position:relative;
}

.sent{
    background:#4e73df;
    color:#fff;
    margin-left:auto;
}

.received{
    background:#f1f5f9;
    color:#333;
}

.time{
    font-size:10px;
    display:block;
    margin-top:5px;
    opacity:0.7;
}

.chat-input{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.chat-input input{
    flex:1;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

.chat-input button{
    background:#4e73df;
    color:#fff;
    border:none;
    padding:10px 15px;
    border-radius:8px;
    cursor:pointer;
}
</style>

</head>

<body>

<div class="chat-container">

    <h2>💬 Chat</h2>

    <div class="chat-box" id="chatBox"></div>

    <div class="chat-input">
        <input type="text" id="msg" placeholder="Type message...">
        <button onclick="sendMsg()">Send</button>
    </div>

</div>

<script>

// LOAD MESSAGES
function loadChat(){
    fetch("load_chat.php")
    .then(res => res.text())
    .then(data=>{
        let box = document.getElementById("chatBox");
        box.innerHTML = data;
        box.scrollTop = box.scrollHeight; // auto scroll
    });
}

// SEND MESSAGE
function sendMsg(){
    let msg = document.getElementById("msg").value;

    if(msg.trim() === "") return;

    fetch("send_chat.php", {
        method:"POST",
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:"message="+encodeURIComponent(msg)
    })
    .then(()=>{
        document.getElementById("msg").value="";
        loadChat();
    });
}

// AUTO LOAD
setInterval(loadChat, 1000);
loadChat();

</script>

</body>
</html>