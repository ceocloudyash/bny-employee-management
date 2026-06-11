<?php
ob_start();
session_start();

if (!isset($_SESSION['username']))
{
    header("Location: index.php");
    exit();
}

include 'db.php';

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

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>View Employee</title>

<style>

body{
background:#020617;
font-family:Segoe UI,sans-serif;
color:white;
padding:20px;
margin:0;
}

.top-bar{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.btn{
padding:10px 15px;
border-radius:8px;
text-decoration:none;
color:white;
}

.back-btn{
background:#06b6d4;
}

.dashboard-btn{
background:#22c55e;
}

.card{
max-width:800px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
box-shadow:0 0 20px rgba(0,0,0,.3);
}

h2{
text-align:center;
color:#22d3ee;
margin-bottom:20px;
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
border:4px solid #22d3ee;
}

.no-photo{
width:140px;
height:140px;
border-radius:50%;
background:#0f172a;
display:flex;
align-items:center;
justify-content:center;
font-size:50px;
margin:auto;
border:4px solid #22d3ee;
}

.info{
background:#0f172a;
padding:15px;
margin-bottom:12px;
border-radius:10px;
font-size:16px;
}

.label{
color:#22d3ee;
font-weight:bold;
}

.actions{
margin-top:20px;
display:flex;
gap:10px;
flex-wrap:wrap;
}

.edit-btn{
background:#f59e0b;
}

.delete-btn{
background:#ef4444;
}

</style>

</head>

<body>

<div class="top-bar">

<a class="btn back-btn" href="employees.php">
⬅ Back To Employees
</a>

<a class="btn dashboard-btn" href="dashboard.php">
🏠 Dashboard
</a>

</div>

<div class="card">

<div class="profile">

<?php
if(!empty($row['profile_photo']))
{
?>

<img src="uploads/<?php echo $row['profile_photo']; ?>" alt="Profile Photo">

<?php
}
else
{
?>

<div class="no-photo">
👤
</div>

<?php
}
?>

</div>

<h2>
<?php echo htmlspecialchars($row['name']); ?>
</h2>

<div class="info">
<span class="label">Employee ID:</span>
<?php echo htmlspecialchars($row['employee_id']); ?>
</div>

<div class="info">
<span class="label">Email:</span>
<?php echo htmlspecialchars($row['email']); ?>
</div>

<div class="info">
<span class="label">Department:</span>
<?php echo htmlspecialchars($row['department']); ?>
</div>

<div class="info">
<span class="label">Position:</span>
<?php echo htmlspecialchars($row['position']); ?>
</div>

<div class="info">
<span class="label">Salary:</span>
₹<?php echo htmlspecialchars($row['salary']); ?>
</div>

<div class="info">
<span class="label">Login Username:</span>
<?php echo htmlspecialchars($row['login_username']); ?>
</div>

<div class="info">
<span class="label">Login Password:</span>
<?php echo htmlspecialchars($row['login_password']); ?>
</div>

<div class="actions">

<a
class="btn edit-btn"
href="edit_employee.php?id=<?php echo urlencode($row['employee_id']); ?>">
✏ Edit Employee </a>

<a
class="btn delete-btn"
href="delete_employee.php?id=<?php echo urlencode($row['employee_id']); ?>"
onclick="return confirm('Are you sure you want to delete this employee?');">
🗑 Delete Employee </a>

</div>

</div>

</body>

</html>