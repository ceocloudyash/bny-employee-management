<?php

session_start();

if(
!isset($_SESSION['role'])
||
$_SESSION['role']!='CEO'
)
{
header("Location:index.php");
exit();
}

include 'db.php';

$message="";

$employees=$conn->query(
"SELECT employee_id,name
FROM employees
ORDER BY name"
);

if(isset($_POST['assign']))
{
$employee_id=$_POST['employee_id'];

$emp=$conn->query(
"SELECT *
FROM employees
WHERE employee_id='$employee_id'"
);

$employee=$emp->fetch_assoc();

$employee_name=$employee['name'];

$title=$_POST['title'];

$description=$_POST['description'];

$deadline=$_POST['deadline'];

$conn->query(
"INSERT INTO tasks
(
employee_id,
employee_name,
task_title,
task_description,
deadline
)
VALUES
(
'$employee_id',
'$employee_name',
'$title',
'$description',
'$deadline'
)"
);

$message="Task Assigned Successfully";
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Task Management</title>

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
text-align:center;
}

select,
input,
textarea{
width:100%;
padding:15px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
}

button{
width:100%;
padding:15px;
background:#06b6d4;
border:none;
border-radius:10px;
color:white;
font-size:16px;
}

.success{
color:#22c55e;
text-align:center;
margin-bottom:15px;
}

.btn{
background:#22c55e;
padding:10px 15px;
color:white;
text-decoration:none;
border-radius:8px;
}

</style>

</head>

<body>

<a class="btn" href="dashboard.php">
🏠 Dashboard
</a>

<div class="container">

<h1>📋 Assign Task</h1>

<?php
if($message!="")
{
echo "<p class='success'>$message</p>";
}
?>

<form method="POST">

<select name="employee_id" required>

<option value="">
Select Employee
</option>

<?php
while($row=$employees->fetch_assoc())
{
?>

<option value="<?php echo $row['employee_id']; ?>">

<?php echo $row['employee_id']; ?>
-
<?php echo $row['name']; ?>

</option>

<?php
}
?>

</select>

<input
type="text"
name="title"
placeholder="Task Title"
required>

<textarea
name="description"
placeholder="Task Description"
required></textarea>

<input
type="date"
name="deadline"
required>

<button
type="submit"
name="assign">

📌 Assign Task

</button>

</form>

</div>

</body>
</html>