<?php

session_start();

if($_SESSION['role'] != 'CEO')
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$message="";

if(isset($_POST['save']))
{

$employee_id=$_POST['employee_id'];

$name=$_POST['name'];

$email=$_POST['email'];

$department=$_POST['department'];

$position=$_POST['position'];

$salary=$_POST['salary'];

$login_username=$_POST['login_username'];

$login_password=$_POST['login_password'];

$photo=$_FILES['photo']['name'];

move_uploaded_file(
$_FILES['photo']['tmp_name'],
"uploads/".$photo
);

$sql="

INSERT INTO employees

(
employee_id,
name,
email,
department,
position,
salary,
photo,
login_username,
login_password
)

VALUES

(
'$employee_id',
'$name',
'$email',
'$department',
'$position',
'$salary',
'$photo',
'$login_username',
'$login_password'
)

";

if($conn->query($sql))
{
$message="Employee Added Successfully";
}
else
{
$message=$conn->error;
}

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Employee</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
}

.container{

width:650px;

margin:30px auto;

background:#1e293b;

padding:30px;

border-radius:20px;

}

h1{
color:#22d3ee;
text-align:center;
}

input{

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

border:none;

background:#06b6d4;

color:white;

border-radius:10px;

cursor:pointer;

}

.msg{
color:lime;
margin-bottom:15px;
}

</style>

</head>

<body>

<div class="container">

<h1>➕ Add Employee</h1>

<div class="msg">
<?php echo $message; ?>
</div>

<form method="POST" enctype="multipart/form-data">

<input
type="text"
name="employee_id"
placeholder="Employee ID"
required>

<input
type="text"
name="name"
placeholder="Employee Name"
required>

<input
type="email"
name="email"
placeholder="Email"
required>

<input
type="text"
name="department"
placeholder="Department"
required>

<input
type="text"
name="position"
placeholder="Position"
required>

<input
type="number"
name="salary"
placeholder="Salary"
required>

<input
type="text"
name="login_username"
placeholder="Employee Username"
required>

<input
type="text"
name="login_password"
placeholder="Employee Password"
required>

<input
type="file"
name="photo">

<button
type="submit"
name="save">

Save Employee

</button>

</form>

</div>

</body>

</html>