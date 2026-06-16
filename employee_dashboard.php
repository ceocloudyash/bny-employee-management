<?php

session_start();

if(
!isset($_SESSION['role'])
||
$_SESSION['role']!='EMPLOYEE'
)
{
header("Location:index.php");
exit();
}

include 'db.php';

$employee_id=$_SESSION['employee_id'];

/* NOTIFICATION COUNT */

$count=$conn->query(
"SELECT COUNT(*) as total
FROM notifications
WHERE employee_id='$employee_id'
AND status='Unread'"
);

$notification_count=0;

if($count)
{
$notification_count=
$count->fetch_assoc()['total'];
}

/* ANNOUNCEMENTS */

$announcements=$conn->query(
"SELECT *
FROM announcements
ORDER BY id DESC
LIMIT 3"
);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>BNY Employee Dashboard</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#020617;
color:white;
display:flex;
}

/* SIDEBAR */

.sidebar{
width:260px;
height:100vh;
background:#0f172a;
position:fixed;
left:0;
top:0;
padding-top:20px;
overflow-y:auto;
box-shadow:0 0 25px rgba(0,0,0,.4);
}

.logo{
text-align:center;
font-size:30px;
font-weight:bold;
color:#22d3ee;
margin-bottom:30px;
}

.sidebar a{
display:block;
padding:15px 25px;
color:white;
text-decoration:none;
transition:.3s;
}

.sidebar a:hover{
background:#1e293b;
border-left:4px solid #22d3ee;
}

/* MAIN */

.main{
margin-left:260px;
width:calc(100% - 260px);
padding:30px;
}

/* WELCOME */

.welcome{
background:#1e293b;
padding:30px;
border-radius:20px;
margin-bottom:25px;
box-shadow:0 0 20px rgba(0,0,0,.3);
}

.welcome h1{
color:#22d3ee;
margin-bottom:10px;
}

/* ANNOUNCEMENTS */

.announcement{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:15px;
border-left:5px solid #22d3ee;
}

.announcement h3{
color:#22d3ee;
margin-bottom:10px;
}

/* CARDS */

.cards{
display:grid;
grid-template-columns:
repeat(auto-fit,minmax(250px,1fr));
gap:20px;
}

.card{
background:#1e293b;
padding:25px;
border-radius:20px;
text-align:center;
transition:.3s;
cursor:pointer;
box-shadow:0 0 20px rgba(0,0,0,.2);
}

.card:hover{
transform:translateY(-8px);
box-shadow:0 0 25px rgba(34,211,238,.4);
}

.card a{
text-decoration:none;
color:white;
display:block;
}

.card h2{
color:#22d3ee;
margin-bottom:10px;
}

/* FOOTER */

.footer{
margin-top:40px;
text-align:center;
color:#94a3b8;
}

@media(max-width:768px){

.sidebar{
width:220px;
}

.main{
margin-left:220px;
width:calc(100% - 220px);
padding:20px;
}

.cards{
grid-template-columns:1fr;
}

}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<div class="logo">
🏦 BNY
</div>

<a href="employee_dashboard.php">
🏠 Dashboard
</a>

<a href="my_profile.php">
👤 My Profile
</a>

<a href="employee_leave.php">
📝 Apply Leave
</a>

<a href="my_leaves.php">
📋 My Leaves
</a>

<a href="attendance.php">
🕒 Attendance
</a>

<a href="my_attendance.php">
📅 My Attendance
</a>

<a href="attendance_calendar.php">

📅 Attendance Calendar
</a>

<a href="holiday_calendar.php">
🎉 Holiday Calendar
</a>
<a href="employee_wfh.php">
🏠 Apply WFH
</a>

<a href="my_wfh.php">
📋 My WFH Requests
</a>

<a href="my_payroll.php">
💵 My Payroll
</a>

<a href="my_tasks.php">
📋 My Tasks
</a>

<a href="chat.php?id=CEO">
💬 Chat With CEO
</a>

<a href="notifications.php">
🔔 Notifications

<?php
if($notification_count>0)
{
echo " ($notification_count)";
}
?>

</a>

<a href="employee_resignation.php">
📝 Submit Resignation
</a>

<a href="my_resignation.php">
📋 My Resignation
</a>

<a href="change_password.php">
🔑 Change Password
</a>

<a href="logout.php">
🚪 Logout
</a>

</div>

<!-- MAIN -->

<div class="main">

<div class="welcome">

<h1>
Welcome,
<?php echo $_SESSION['employee_name']; ?>
</h1>

<p>
Employee ID:
<strong>
<?php echo $_SESSION['employee_id']; ?>
</strong>
</p>

<br>

<p>
Welcome to the BNY Employee Management System.
</p>

</div>

<h2
style="
color:#22d3ee;
margin-bottom:15px;
">
📢 Latest Announcements
</h2>

<?php

if(
$announcements
&&
$announcements->num_rows>0
)
{

while(
$a=$announcements->fetch_assoc()
)
{

?>

<div class="announcement">

<h3>
📢 <?php echo $a['title']; ?>
</h3>

<p>
<?php echo $a['message']; ?>
</p>

</div>

<?php

}

}
else
{

?>

<div class="announcement">

<h3>
📢 No Announcements
</h3>

<p>
There are currently no announcements.
</p>

</div>

<?php

}

?>

<br>

<div class="cards">

<div class="card">
<a href="my_profile.php">
<h2>👤 My Profile</h2>
<p>View employee information</p>
</a>
</div>

<div class="card">
<a href="employee_leave.php">
<h2>📝 Apply Leave</h2>
<p>Submit a leave request</p>
</a>
</div>

<div class="card">
<a href="my_leaves.php">
<h2>📋 My Leaves</h2>
<p>Track leave approvals</p>
</a>
</div>

<div class="card">
<a href="attendance.php">
<h2>🕒 Attendance</h2>
<p>Check In / Check Out</p>
</a>
</div>

<div class="card">
<a href="my_attendance.php">
<h2>📅 My Attendance</h2>
<p>View attendance history</p>
</a>
</div>

<div class="card">
<a href="attendance_calendar.php">
<h2>📅 Attendance Calendar</h2>
<p>Calendar view of attendance</p>
</a>
</div>
<div class="card">

<a href="holiday_calendar.php">

<h2>🎉 Holiday Calendar</h2>

<p>View company holidays</p>

</a>

</div>

<div class="card">
<a href="my_payroll.php">
<h2>💵 My Payroll</h2>
<p>View salary records</p>
</a>
</div>

<div class="card">
<a href="my_tasks.php">
<h2>📋 My Tasks</h2>
<p>View assigned tasks</p>
</a>
</div>

<div class="card">
<a href="notifications.php">
<h2>🔔 Notifications</h2>
<p>Read company notifications</p>
</a>
</div>

<div class="card">
<a href="employee_resignation.php">
<h2>📝 Submit Resignation</h2>
<p>Send resignation request</p>
</a>
</div>

<div class="card">
<a href="my_resignation.php">
<h2>📋 My Resignation</h2>
<p>Track resignation status</p>
</a>
</div>

<div class="card">
<a href="chat.php?id=CEO">
<h2>💬 Chat With CEO</h2>
<p>Send messages to CEO</p>
</a>
</div>

<div class="card">
<a href="change_password.php">
<h2>🔑 Change Password</h2>
<p>Update your password</p>
</a>
</div>

<div class="card">
<a href="logout.php">
<h2>🚪 Logout</h2>
<p>Securely sign out</p>
</a>
</div>

</div>

<div class="footer">

<br><br>

© 2026 BNY Employee Management System
Made By Yash Dwivedi

</div>

</div>

</body>
</html>