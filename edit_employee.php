<?php

ob_start();
session_start();

if (!isset($_SESSION['username']))
{
    header("Location: index.php");
    exit();
}

include 'db.php';

$message = "";

if (!isset($_GET['id']))
{
    die("Employee ID Missing");
}

$employee_id = $_GET['id'];

$result = $conn->query(
"SELECT * FROM employees
WHERE employee_id='$employee_id'"
);

if (!$result || $result->num_rows == 0)
{
    die("Employee Not Found");
}

$row = $result->fetch_assoc();

if (isset($_POST['update']))
{
    $new_employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    $photo = $row['profile_photo'];

    if (
        isset($_FILES['profile_photo']) &&
        $_FILES['profile_photo']['name'] != ""
    )
    {
        if (!is_dir("uploads"))
        {
            mkdir("uploads", 0777, true);
        }

        $photo =
        time() . "_" .
        basename($_FILES['profile_photo']['name']);

        move_uploaded_file(
            $_FILES['profile_photo']['tmp_name'],
            "uploads/" . $photo
        );
    }

    $update = "
    UPDATE employees
    SET
    employee_id='$new_employee_id',
    name='$name',
    email='$email',
    department='$department',
    position='$position',
    salary='$salary',
    login_username='$login_username',
    login_password='$login_password',
    profile_photo='$photo'
    WHERE employee_id='$employee_id'
    ";

    if ($conn->query($update))
    {
        $message =
        "✅ Employee Updated Successfully";

        $employee_id = $new_employee_id;

        $result = $conn->query(
        "SELECT * FROM employees
        WHERE employee_id='$employee_id'"
        );

        $row = $result->fetch_assoc();
    }
    else
    {
        $message =
        "❌ Error : " .
        $conn->error;
    }
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Employee</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:20px;
margin:0;
}

.top-bar{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.dashboard-btn,
.back-btn{
padding:10px 15px;
text-decoration:none;
color:white;
border-radius:8px;
}

.dashboard-btn{
background:#22c55e;
}

.back-btn{
background:#06b6d4;
}

.container{
max-width:800px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h2{
color:#22d3ee;
}

.message{
background:#14532d;
padding:12px;
border-radius:8px;
margin-bottom:15px;
}

input{
width:100%;
padding:14px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
box-sizing:border-box;
}

button{
width:100%;
padding:15px;
background:#06b6d4;
border:none;
border-radius:10px;
color:white;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#0891b2;
}

.profile{
text-align:center;
margin-bottom:20px;
}

.profile img{
width:140px;
height:140px;
border-radius:50%;
object-fit:cover;
border:3px solid #22d3ee;
}

label{
display:block;
margin-bottom:8px;
}

</style>

</head>

<body>

<div class="top-bar">

<a class="back-btn"
href="employees.php">
⬅ Employees </a>

<a class="dashboard-btn"
href="dashboard.php">
🏠 Dashboard </a>

</div>

<div class="container">

<h2>✏ Edit Employee</h2>

<?php
if($message!="")
{
echo "<div class='message'>$message</div>";
}
?>

<div class="profile">

<?php
if(!empty($row['profile_photo']))
{
?>

<img src="uploads/<?php echo $row['profile_photo']; ?>">

<?php
}
?>

</div>

<form
method="POST"
enctype="multipart/form-data">

<label>Employee ID</label> <input
type="text"
name="employee_id"
value="<?php echo $row['employee_id']; ?>"
required>

<label>Name</label> <input
type="text"
name="name"
value="<?php echo $row['name']; ?>"
required>

<label>Email</label> <input
type="email"
name="email"
value="<?php echo $row['email']; ?>"
required>

<label>Department</label> <input
type="text"
name="department"
value="<?php echo $row['department']; ?>"
required>

<label>Position</label> <input
type="text"
name="position"
value="<?php echo $row['position']; ?>"
required>

<label>Salary</label> <input
type="number"
name="salary"
value="<?php echo $row['salary']; ?>"
required>

<label>Login Username</label> <input
type="text"
name="login_username"
value="<?php echo $row['login_username']; ?>"
required>

<label>Login Password</label> <input
type="text"
name="login_password"
value="<?php echo $row['login_password']; ?>"
required>

<label>Profile Photo</label> <input
type="file"
name="profile_photo">

<button
type="submit"
name="update">

💾 Update Employee

</button>

</form>

</div>

</body>

</html>