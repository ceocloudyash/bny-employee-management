<?php

session_start();

if($_SESSION['role']!='CEO')
{
header("Location:index.php");
exit();
}

include 'db.php';

if(isset($_POST['save']))
{

$name=$_POST['holiday_name'];

$date=$_POST['holiday_date'];

$description=$_POST['description'];

$conn->query(
"INSERT INTO holidays
(
holiday_name,
holiday_date,
description
)
VALUES
(
'$name',
'$date',
'$description'
)"
);

echo "<script>
alert('Holiday Added');
window.location='holiday_list.php';
</script>";

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Holiday</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:30px;
}

.container{
max-width:600px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

input,textarea{
width:100%;
padding:12px;
margin:10px 0;
background:#0f172a;
color:white;
border:none;
border-radius:10px;
}

button{
width:100%;
padding:15px;
background:#22d3ee;
border:none;
border-radius:10px;
font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<h2>📅 Add Holiday</h2>

<form method="POST">

<input
type="text"
name="holiday_name"
placeholder="Holiday Name"
required>

<input
type="date"
name="holiday_date"
required>

<textarea
name="description"
placeholder="Description"></textarea>

<button
name="save">

Add Holiday

</button>

</form>

</div>

</body>
</html>