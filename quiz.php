<?php
$score = 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Smart Quiz</title>
<link rel="stylesheet" href="css/style.css">

<style>
/* ===== MAIN QUIZ UI ===== */
.quiz-container{
    max-width:600px;
    margin:50px auto;
    padding:20px;
}

.quiz-title{
    text-align:center;
    margin-bottom:15px;
}

/* TIMER */
.timer{
    text-align:right;
    font-size:18px;
    font-weight:bold;
    color:#ef4444;
    margin-bottom:10px;
}

/* CARD */
.quiz-card{
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

/* QUESTION */
.question-title{
    margin-bottom:15px;
}

/* OPTIONS */
.option{
    display:block;
    background:#f1f5f9;
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
    cursor:pointer;
    transition:0.3s;
}

.option:hover{
    background:#e0e7ff;
}

.option input{
    margin-right:8px;
}

/* BUTTON */
.next-btn{
    margin-top:10px;
    background:#4f46e5;
    color:#fff;
    border:none;
    padding:10px 15px;
    border-radius:8px;
    cursor:pointer;
}

/* RESULT POPUP */
.result-popup{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
}

.result-box{
    background:#fff;
    padding:30px;
    border-radius:15px;
    text-align:center;
    width:300px;
    animation:pop 0.4s ease;
}

@keyframes pop{
    from{transform:scale(0.7); opacity:0;}
    to{transform:scale(1); opacity:1;}
}

/* SHOW ONLY ACTIVE QUESTION */
.quiz-step{
    display:none;
}

.quiz-step.active{
    display:block;
    animation:fade 0.3s ease;
}

@keyframes fade{
    from{opacity:0; transform:translateX(20px);}
    to{opacity:1; transform:translateX(0);}
}
</style>

</head>

<body>

<div class="quiz-container">

    <h2 class="quiz-title">Smart Quiz</h2>

    <!-- TIMER -->
    <div class="timer">⏱ Time: <span id="time">30</span>s</div>

    <div class="quiz-card">

        <form id="quizForm">

            <!-- QUESTION 1 -->
            <div class="quiz-step active">
                <h3 class="question-title">1. Which is scripting language?</h3>

                <label class="option">
                    <input type="radio" name="q1" value="js"> JavaScript
                </label>

                <label class="option">
                    <input type="radio" name="q1" value="c"> C
                </label>

                <button type="button" class="next-btn" onclick="nextQuestion()">Next ➡</button>
            </div>

            <!-- QUESTION 2 -->
            <div class="quiz-step">
                <h3 class="question-title">2. Which is markup language?</h3>

                <label class="option">
                    <input type="radio" name="q2" value="html"> HTML
                </label>

                <label class="option">
                    <input type="radio" name="q2" value="java"> Java
                </label>

                <button type="button" class="next-btn" onclick="submitQuiz()">Submit ✅</button>
            </div>

        </form>

    </div>

</div>

<!-- RESULT POPUP -->
<div class="result-popup" id="resultPopup">
    <div class="result-box">
        <h2 id="scoreText"></h2>
        <div id="certificateBtn"></div>
        <br>
        <button class="next-btn" onclick="closePopup()">Close</button>
    </div>
</div>

<script>

/* ===== QUESTION NAVIGATION ===== */
let current = 0;
let questions = document.querySelectorAll(".quiz-step");

function nextQuestion(){

    let selected = document.querySelector(`.quiz-step.active input:checked`);

    if(!selected){
        alert("⚠️ Please select an answer first!");
        return;
    }

    if(current < questions.length - 1){
        questions[current].classList.remove("active");
        current++;
        questions[current].classList.add("active");
    }
}

/* ===== TIMER ===== */
let time = 30;

let timer = setInterval(()=>{
    time--;
    document.getElementById("time").innerText = time;

    if(time <= 0){
        clearInterval(timer);
        submitQuiz();
    }
},1000);

/* ===== SUBMIT QUIZ ===== */
function submitQuiz(){

    clearInterval(timer);

    let score = 0;

    let q1 = document.querySelector('input[name="q1"]:checked');
    let q2 = document.querySelector('input[name="q2"]:checked');

    if(q1 && q1.value === "js") score += 5;
    if(q2 && q2.value === "html") score += 5;

    // SHOW POPUP
    document.getElementById("resultPopup").style.display = "flex";
    document.getElementById("scoreText").innerText = "Your Score: " + score + "/10";

    if(score >= 5){
        document.getElementById("certificateBtn").innerHTML =
            "<a href='certificate.php' class='next-btn'>🎓 Download Certificate</a>";
    } else {
        document.getElementById("certificateBtn").innerHTML =
            "<p style='color:red;'>❌ Try Again</p>";
    }
}

/* ===== CLOSE POPUP ===== */
function closePopup(){
    document.getElementById("resultPopup").style.display = "none";
}

</script>

</body>
</html>