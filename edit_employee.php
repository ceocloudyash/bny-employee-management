
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

if($result->num_rows == 0)
{
    die("Employee not found");
}

$row = $result->fetch_assoc();

$message = "";

if(isset($_POST['update']))
{
    $new_employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    $photo_sql = "";

    if(!empty($_FILES['profile_photo']['name']))
    {
        $photo =
        time() . "_" .
        basename($_FILES['profile_photo']['name']);

        move_uploaded_file(
        $_FILES['profile_photo']['tmp_name'],
        "uploads/" . $photo
        );

        $photo_sql =
        ", profile_photo='$photo'";
    }

    $sql = "
    UPDATE employees
    SET
    employee_id='$new_employee_id',
    name='$name',
    email='$email',
    department='$department',
    position='$position',
    salary='$salary',
    login_username='$login_username',
    login_password='$login_password'
    $photo_sql
    WHERE employee_id='$employee_id'
    ";

    if($conn->query($sql))
    {
        $message = "Employee Updated Successfully";
    }

    $result = $conn->query(
    "SELECT * FROM employees
    WHERE employee_id='$new_employee_id'"
    );

    $row = $result->fetch_assoc();
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

.dashboard-btn:hover{
background:#16a34a;
}

.back-btn{
background:#06b6d4;
}

.back-btn:hover{
background:#0891b2;
}

.container{
max-width:700px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h2{
color:#22d3ee;
margin-bottom:20px;
}

.success{
background:#14532d;
padding:12px;
border-radius:8px;
margin-bottom:15px;
}

input{
width:100%;
padding:15px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
box-sizing:border-box;
}

label{
display:block;
margin-bottom:8px;
color:#cbd5e1;
}

button{
width:100%;
padding:15px;
background:#06b6d4;
border:none;
border-radius:10px;
color:white;
cursor:pointer;
font-size:16px;
}

button:hover{
background:#0891b2;
}

.profile{
text-align:center;
margin-bottom:20px;
}

.profile img{
width:120px;
height:120px;
border-radius:50%;
object-fit:cover;
border:3px solid #22d3ee;
}

</style>

</head>

<body>

<div class="top-bar">

<a class="back-btn" href="employees.php">
⬅ Employees
</a>

<?php if($_SESSION['role']=='CEO') { ?>

<a class="dashboard-btn" href="dashboard.php">
🏠 Dashboard
</a>

<?php } else { ?>

<a class="dashboard-btn" href="employee_dashboard.php">
🏠 Dashboard
</a>

<?php } ?>

</div>

<div class="container">

<h2>✏ Edit Employee</h2>

<?php
if($message!="")
{
    echo "<div class='success'>$message</div>";
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

<form method="POST" enctype="multipart/form-data">

<input
type="text"
name="employee_id"
value="<?php echo $row['employee_id']; ?>"
required>

<input
type="text"
name="name"
value="<?php echo $row['name']; ?>"
required>

<input
type="email"
name="email"
value="<?php echo $row['email']; ?>"
required>

<input
type="text"
name="department"
value="<?php echo $row['department']; ?>"
required>

<input
type="text"
name="position"
value="<?php echo $row['position']; ?>"
required>

<input
type="number"
name="salary"
value="<?php echo $row['salary']; ?>"
required>

<input
type="text"
name="login_username"
value="<?php echo $row['login_username']; ?>"
required>

<input
type="text"
name="login_password"
value="<?php echo $row['login_password']; ?>"
required>

<label>Profile Photo</label>

<input
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

