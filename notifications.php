<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];

$result = $conn->query(
"SELECT *
FROM notifications
WHERE employee_id='$employee_id'
ORDER BY id DESC"
);
$conn->query(
"UPDATE notifications
SET status='Read'
WHERE employee_id='$employee_id'"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Notifications</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:30px;
}

h1{
color:#22d3ee;
}

.card{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:15px;
}

.title{
color:#22d3ee;
font-weight:bold;
font-size:18px;
}

.time{
font-size:12px;
color:#94a3b8;
margin-top:10px;
}

.dashboard{
background:#22c55e;
color:white;
padding:10px 15px;
text-decoration:none;
border-radius:8px;
}

</style>

</head>

<body>

<a
class="dashboard"
href="employee_dashboard.php">

🏠 Dashboard

</a>

<br><br>

<h1>🔔 My Notifications</h1>

<?php

if($result->num_rows>0)
{
while($row=$result->fetch_assoc())
{
?>

<div class="card">

<div class="title">
<?php echo $row['title']; ?>
</div>

<br>

<?php echo $row['message']; ?>

<div class="time">

<?php echo $row['created_at']; ?>

</div>

</div>

<?php
}
}
else
{
echo "<p>No Notifications Found</p>";
}

?>

</body>
</html>