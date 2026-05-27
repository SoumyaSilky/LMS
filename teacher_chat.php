<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$teacher = $conn->query("SELECT * FROM users WHERE email='$user'")->fetch_assoc();
$teacher_id = $teacher['id'];

// GET ALL STUDENTS
$students = $conn->query("SELECT * FROM users WHERE role='student'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Teacher Chat</title>
<link rel="stylesheet" href="css/style.css">

<style>
.chat-wrapper{
    display:flex;
    height:80vh;
}

/* LEFT PANEL */
.users{
    width:250px;
    background:#1e293b;
    color:white;
    padding:10px;
    overflow-y:auto;
}

.user{
    padding:10px;
    cursor:pointer;
    border-radius:6px;
}

.user:hover{
    background:#334155;
}

/* RIGHT CHAT */
.chat-area{
    flex:1;
    display:flex;
    flex-direction:column;
    background:#f1f5f9;
}

.chat-box{
    flex:1;
    padding:10px;
    overflow-y:auto;
}

.message{
    max-width:60%;
    padding:10px;
    border-radius:10px;
    margin:5px 0;
}

.sent{
    background:#4e73df;
    color:white;
    margin-left:auto;
}

.received{
    background:white;
}

/* INPUT */
.chat-input{
    display:flex;
    padding:10px;
    background:#fff;
}

.chat-input input{
    flex:1;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
}

.chat-input button{
    margin-left:10px;
    background:#4e73df;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:6px;
}
</style>

</head>

<body>

<div class="chat-wrapper">

<!-- 👨‍🎓 STUDENT LIST -->
<div class="users">
    <h3>Students</h3>

    <?php while($s = $students->fetch_assoc()){ ?>
        <div class="user" onclick="openChat(<?php echo $s['id']; ?>)">
            <?php echo $s['name']; ?>
        </div>
    <?php } ?>
</div>

<!-- 💬 CHAT AREA -->
<div class="chat-area">

    <div class="chat-box" id="chatBox">
        <p>Select a student to start chat</p>
    </div>

    <div class="chat-input">
        <input type="text" id="msg" placeholder="Type message...">
        <button onclick="sendMsg()">Send</button>
    </div>

</div>

</div>


<script>
let teacher_id = "<?php echo $teacher_id; ?>";
let currentUser = 0;

// OPEN CHAT
function openChat(id){
    currentUser = id;
    loadChat();
}

// LOAD CHAT
function loadChat(){
    if(currentUser == 0) return;

    fetch("load_teacher_chat.php?user_id=" + currentUser + "&teacher_id=" + teacher_id)
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("chatBox").innerHTML = data;
    });
}

// SEND MESSAGE
function sendMsg(){
    let msg = document.getElementById("msg").value;

    fetch("send_teacher_chat.php", {
        method:"POST",
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:"msg="+msg+"&user_id="+currentUser
    }).then(()=>{
        document.getElementById("msg").value="";
        loadChat();
    });
}

// AUTO REFRESH
setInterval(loadChat, 1000);
</script>

</body>
</html>