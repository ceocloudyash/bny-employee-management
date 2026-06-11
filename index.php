<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BNY Employee Portal</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
overflow:hidden;
background:linear-gradient(
135deg,
#020617,
#0f172a,
#1e293b
);
}

/* Animated Background */

body::before{
content:'';
position:absolute;
width:500px;
height:500px;
background:#06b6d4;
border-radius:50%;
top:-200px;
left:-150px;
filter:blur(180px);
opacity:.25;
animation:float1 8s infinite alternate;
}

body::after{
content:'';
position:absolute;
width:450px;
height:450px;
background:#22d3ee;
border-radius:50%;
bottom:-180px;
right:-120px;
filter:blur(180px);
opacity:.2;
animation:float2 8s infinite alternate;
}

@keyframes float1{
from{transform:translateY(0);}
to{transform:translateY(80px);}
}

@keyframes float2{
from{transform:translateY(0);}
to{transform:translateY(-80px);}
}

/* Login Card */

.card{

position:relative;
z-index:10;

width:450px;

padding:45px;

background:rgba(255,255,255,0.06);

backdrop-filter:blur(20px);

border:1px solid rgba(255,255,255,0.1);

border-radius:30px;

box-shadow:
0 20px 50px rgba(0,0,0,.4),
0 0 40px rgba(34,211,238,.08);

animation:fadeUp .8s ease;
}

@keyframes fadeUp{
from{
opacity:0;
transform:translateY(30px);
}
to{
opacity:1;
transform:translateY(0);
}
}

.logo{
font-size:65px;
text-align:center;
margin-bottom:10px;
}

h1{
text-align:center;
color:#22d3ee;
font-size:30px;
margin-bottom:8px;
}

.subtitle{
text-align:center;
color:#94a3b8;
margin-bottom:30px;
font-size:14px;
}

/* Inputs */

.input-group{
position:relative;
margin-bottom:18px;
}

input{

width:100%;
padding:16px 18px;

background:rgba(15,23,42,.9);

border:1px solid rgba(255,255,255,.05);

border-radius:14px;

color:white;

font-size:15px;

outline:none;

transition:.3s;
}

input:focus{
border-color:#22d3ee;
box-shadow:0 0 15px rgba(34,211,238,.3);
transform:translateY(-2px);
}

.toggle-password{
position:absolute;
right:15px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
color:#94a3b8;
}

/* Button */

button{

width:100%;
padding:16px;

border:none;

border-radius:14px;

background:linear-gradient(
135deg,
#06b6d4,
#22d3ee
);

color:white;

font-size:16px;

font-weight:600;

cursor:pointer;

transition:.3s;
}

button:hover{

transform:translateY(-3px);

box-shadow:
0 10px 25px rgba(34,211,238,.35);

}

/* Footer */

.footer{
text-align:center;
margin-top:20px;
color:#94a3b8;
font-size:13px;
}

.footer span{
color:#22d3ee;
font-weight:600;
}

/* Responsive */

@media(max-width:500px){

.card{
width:90%;
padding:30px;
}

h1{
font-size:24px;
}

}

</style>

</head>

<body>

<div class="card">

<div class="logo">🏦</div>

<h1>BNY Employee Portal</h1>

<p class="subtitle">
Secure Employee Management System
</p>

<form action="login.php" method="POST">

<div class="input-group">

<input
type="text"
name="username"
placeholder="👤 Username"
required>

</div>

<div class="input-group">

<input
type="password"
name="password"
id="password"
placeholder="🔒 Password"
required>

<span
class="toggle-password"
onclick="togglePassword()">

👁

</span>

</div>

<button type="submit">

🚀 Sign In

</button>

</form>

<div class="footer">

Powered by <span>BNY HRMS</span>

</div>

</div>

<script>

function togglePassword()
{
let password =
document.getElementById("password");

if(password.type==="password")
{
password.type="text";
}
else
{
password.type="password";
}
}

</script>

</body>

</html>