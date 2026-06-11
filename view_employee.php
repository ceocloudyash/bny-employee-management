
<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

if(!isset($_GET['id']))
{
    die("Employee ID Missing");
}

$employee_id = $_GET['id'];

$result = $conn->query(
"SELECT * FROM employees
WHERE employee_id='$employee_id'"
);

if(!$result || $result->num_rows == 0)
{
    die("Employee Not Found");
}

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

<title>View Employee</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:20px;
}

.card{
max-width:700px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h2{
color:#22d3ee;
text-align:center;
}

img{
width:120px;
height:120px;
border-radius:50%;
object-fit:cover;
display:block;
margin:auto;
margin-bottom:20px;
border:3px solid #22d3ee;
}

.info{
background:#0f172a;
padding:12px;
margin-bottom:10px;
border-radius:10px;
}

.btn{
display:inline-block;
padding:10px 15px;
background:#06b6d4;
color:white;
text-decoration:none;
border-radius:8px;
margin-top:15px;
}

</style>

</head>

<body>

<div class="card">

<?php if(!empty($row['profile_photo'])) { ?>

<img src="uploads/<?php echo $row['profile_photo']; ?>">

<?php } ?>

<h2><?php echo $row['name']; ?></h2>

<div class="info">
Employee ID: <?php echo $row['employee_id']; ?>
</div>

<div class="info">
Email: <?php echo $row['email']; ?>
</div>

<div class="info">
Department: <?php echo $row['department']; ?>
</div>

<div class="info">
Position: <?php echo $row['position']; ?>
</div>

<div class="info">
Salary: ₹<?php echo $row['salary']; ?>
</div>

<div class="info">
Username: <?php echo $row['login_username']; ?>
</div>

<a class="btn" href="employees.php">
⬅ Back To Employees
</a>

</div>

</body>

</html>
