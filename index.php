<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>PROGRAMMING ADDA</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
    scroll-behavior:smooth;
}

/* 🎥 VIDEO */
.video-bg{
    position:fixed;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:-3;
    animation:zoom 20s infinite alternate;
}
@keyframes zoom{
    from{transform:scale(1);}
    to{transform:scale(1.15);}
}

/* 🌙 OVERLAY */
.overlay{
    position:fixed;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.65);
    z-index:-2;
}

/* 🔥 NAVBAR */
.navbar{
    display:flex;
    justify-content:space-between;
    padding:20px 60px;
    background:rgba(0,0,0,0.3);
    backdrop-filter:blur(10px);
    color:white;
    position:fixed;
    width:100%;
    z-index:100;
}

.navbar a{
    margin-left:15px;
    padding:8px 18px;
    border-radius:25px;
    text-decoration:none;
    color:white;
    border:1px solid rgba(255,255,255,0.2);
    transition:0.3s;
}
.navbar a:hover{
    background:#6366f1;
}

/* 🚀 HERO */
.hero{
    height:100vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    color:white;
}

.hero h1{
    font-size:60px;
    background:linear-gradient(90deg,#6366f1,#22c55e);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* ✨ TYPING TEXT */
.typing{
    font-size:22px;
    color:#fff;
    margin-top:10px;
}

/* BUTTONS */
.btn{
    padding:14px 30px;
    margin:15px;
    border:none;
    border-radius:30px;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}
.primary{
    background:linear-gradient(135deg,#6366f1,#7c3aed);
    color:white;
}
.secondary{
    background:rgba(255,255,255,0.1);
    color:white;
    border:1px solid rgba(255,255,255,0.3);
}
.btn:hover{
    transform:scale(1.1);
}

/* 💎 FEATURES */
.features{
    padding:100px 60px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
    background:#f8fafc;
}

.feature-card{
    background:rgba(255,255,255,0.8);
    backdrop-filter:blur(10px);
    padding:30px;
    border-radius:20px;
    text-align:center;
    transition:0.4s;
    opacity:0;
    transform:translateY(50px);
}

.feature-card.show{
    opacity:1;
    transform:translateY(0);
}

.feature-card:hover{
    transform:translateY(-10px) scale(1.03);
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
}

/* 🚀 CTA SECTION */
.cta{
    padding:80px;
    text-align:center;
    background:linear-gradient(135deg,#6366f1,#22c55e);
    color:white;
}

/* FOOTER */
.footer{
    text-align:center;
    padding:20px;
    background:#0f172a;
    color:white;
}
</style>
</head>

<body>

<!-- 🎥 VIDEO BACKGROUND (NEW WORKING SOURCE) -->
<video autoplay muted loop playsinline class="video-bg">
    <source src="videos/coding.mp4" type="video/mp4">
</video>

<div class="overlay"></div>

<!-- 🔥 NAVBAR -->
<div class="navbar">
    <h2>💻PROGRAMMING ADDA 💻</h2>
    <div>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
</div>

<!-- 🚀 HERO -->
<div class="hero"> 
    <h1>Upgrade Your Learning With Adda: WHERE HARDWORK MEET SUCCESS</h1>
    <div class="typing" id="typing"></div>

    <div>
        <button class="btn primary" onclick="location.href='register.php'">Get Started</button>
        <button class="btn secondary" onclick="location.href='explore.php'">Explore Courses</button>
    </div>
</div>

<!-- 💎 FEATURES -->
<section class="features">

    <div class="feature-card">📚 <h3>Courses</h3><p>Learn anytime</p></div>
    <div class="feature-card">📊 <h3>Progress</h3><p>Track growth</p></div>
    <div class="feature-card">📝 <h3>Assignments</h3><p>Submit tasks</p></div>
    <div class="feature-card">🎓 <h3>Certificates</h3><p>Get certified</p></div>

</section>

<!-- 🚀 CTA -->
<section class="cta">
    <h2>Start Your Coding Journey Today With Adda</h2>
    <br>
    <button class="btn primary" onclick="location.href='register.php'">Join Now</button>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>© 2026 PROGRAMMING ADDA | Where Hardwork Meet Success 🔥</p>
</footer>

<script>
/* ✨ TYPING EFFECT */
const texts = ["Learn JavaScript 💻","Master Python 🐍","Build Projects 🚀","Get Job Ready 💼","And Many More Things"];
let i=0,j=0,current="",isDeleting=false;

function type(){
    current = texts[i];
    document.getElementById("typing").innerHTML = current.substring(0,j);

    if(!isDeleting && j<current.length){
        j++;
        setTimeout(type,80);
    }
    else if(isDeleting && j>0){
        j--;
        setTimeout(type,40);
    }
    else{
        isDeleting = !isDeleting;
        if(!isDeleting) i=(i+1)%texts.length;
        setTimeout(type,800);
    }
}
type();

/* 🎯 SCROLL ANIMATION */
window.addEventListener("scroll",()=>{
    document.querySelectorAll(".feature-card").forEach(el=>{
        let pos = el.getBoundingClientRect().top;
        if(pos < window.innerHeight - 100){
            el.classList.add("show");
        }
    });
});
</script>

</body>
</html>