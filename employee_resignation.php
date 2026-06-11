<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
header("Location:index.php");
exit();
}

include 'db.php';

$message="";

if(isset($_POST['submit']))
{
$employee_id=$_SESSION['employee_id'];
$employee_name=$_SESSION['employee_name'];
$reason=$_POST['reason'];

$conn->query(
"INSERT INTO resignations
(
employee_id,
employee_name,
reason
)
VALUES
(
'$employee_id',
'$employee_name',
'$reason'
)"
);

$message="Resignation Submitted Successfully";
}

?>

<!DOCTYPE html>

<html>
<head>

<title>Submit Resignation</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:30px;
}

.container{
max-width:700px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h1{
color:#22d3ee;
margin-bottom:20px;
}

textarea{
width:100%;
height:180px;
padding:15px;
background:#0f172a;
color:white;
border:none;
border-radius:10px;
}

button{
width:100%;
padding:15px;
margin-top:15px;
background:#06b6d4;
border:none;
border-radius:10px;
color:white;
font-size:16px;
}

.success{
color:#22c55e;
margin-bottom:15px;
}

</style>

</head>

<body>

<div class="container">

<h1>📝 Submit Resignation</h1>

<?php
if($message!="")
{
echo "<div class='success'>$message</div>";
}
?>

<form method="POST">

<textarea
name="reason"
required
placeholder="Enter resignation reason"></textarea>

<button
name="submit">

Submit Resignation

</button>

</form>

</div>

</body>
</html>
