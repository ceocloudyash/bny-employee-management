<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'CEO')
{
    header("Location:index.php");
    exit();
}

include 'db.php';

/* Dashboard Statistics */

$totalEmployees = 0;
$totalLeaves = 0;
$pendingLeaves = 0;
$totalPayroll = 0;

$result = $conn->query("SELECT COUNT(*) AS total FROM employees");
if($result){
    $totalEmployees = $result->fetch_assoc()['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM leave_requests");
if($result){
    $totalLeaves = $result->fetch_assoc()['total'];
}

$result = $conn->query("SELECT COUNT(*) AS total FROM leave_requests WHERE status='Pending'");
if($result){
    $pendingLeaves = $result->fetch_assoc()['total'];
}

$result = $conn->query("SELECT SUM(net_salary) AS total FROM payroll");
if($result){
    $row = $result->fetch_assoc();
    $totalPayroll = $row['total'] ? $row['total'] : 0;
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>BNY CEO Dashboard</title>

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
box-shadow:0 0 20px rgba(0,0,0,0.5);
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

.header{
margin-bottom:25px;
}

.header h1{
color:#22d3ee;
margin-bottom:10px;
}

/* WELCOME */

.welcome{
background:#1e293b;
padding:25px;
border-radius:20px;
margin-bottom:25px;
box-shadow:0 0 20px rgba(0,0,0,.3);
}

.welcome h2{
color:#22d3ee;
margin-bottom:10px;
}

/* STATS */

.stats{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;
}

.stat-card{
background:#1e293b;
padding:25px;
border-radius:20px;
text-align:center;
box-shadow:0 0 15px rgba(0,0,0,.3);
transition:.3s;
}

.stat-card:hover{
transform:translateY(-5px);
}

.stat-card h2{
font-size:35px;
color:#22d3ee;
margin-bottom:10px;
}

/* CARDS */

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:20px;
}

.card{
background:#1e293b;
padding:25px;
border-radius:20px;
box-shadow:0 0 15px rgba(0,0,0,.3);
transition:.3s;
}

.card:hover{
transform:translateY(-5px);
}

.card h2{
color:#22d3ee;
margin-bottom:10px;
}

.card p{
margin-bottom:15px;
}

.card a{
display:inline-block;
padding:10px 15px;
background:#22d3ee;
color:black;
text-decoration:none;
border-radius:10px;
font-weight:bold;
}

/* FOOTER */

.footer{
margin-top:40px;
text-align:center;
color:#94a3b8;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<div class="logo">
🏦 BNY
</div>

<a href="dashboard.php">📊 Dashboard</a>

<a href="employees.php">👥 Employees</a>

<a href="add_employee.php">➕ Add Employee</a>

<a href="resignation_requests.php">
📝 Resignation Requests
</a>


<a href="leave_requests.php">📋 Leave Requests</a>
<a href="chat_users.php">
💬 Employee Chats
</a>
<a href="wfh_requests.php">
🏠 WFH Requests
</a>
<a href="task_management.php">
📋 Task Management
</a>

<a href="payroll.php">💰 Payroll</a>

<a href="task_report.php">
📊 Task Reports
</a>



<a href="change_password.php">🔑 Change Password</a>

<a href="logout.php">🚪 Logout</a>

</div>

<!-- MAIN -->

<div class="main">

<div class="header">

<h1>CEO Dashboard</h1>

<p>Bank of New York Employee Management System</p>

</div>

<div class="welcome">

<h2>
Welcome,
<?php echo $_SESSION['username']; ?>
</h2>

<p>
You have full administrative access to the BNY Employee Management System.
</p>

</div>

<!-- STATISTICS -->

<div class="stats">

<div class="stat-card">
<h2><?php echo $totalEmployees; ?></h2>
<p>Total Employees</p>
</div>

<div class="stat-card">
<h2><?php echo $totalLeaves; ?></h2>
<p>Leave Requests</p>
</div>

<div class="stat-card">
<h2><?php echo $pendingLeaves; ?></h2>
<p>Pending Leaves</p>
</div>

<div class="stat-card">
<h2>₹<?php echo number_format($totalPayroll); ?></h2>
<p>Total Payroll</p>
</div>

</div>

<!-- ACTION CARDS -->

<div class="cards">

<div class="card">
<h2>👥 Employees</h2>
<p>Manage employee records.</p>
<a href="employees.php">Open</a>
</div>

<div class="card">
<h2>➕ Add Employee</h2>
<p>Create new employee accounts.</p>
<a href="add_employee.php">Open</a>
</div>

<div class="card">
<h2>📋 Leave Requests</h2>
<p>Approve or reject employee leaves.</p>
<a href="leave_requests.php">Open</a>
</div>

<div class="card">
<h2>🏠 WFH Requests</h2>
<p>Approve or reject WFH requests.</p>
<a href="wfh_requests.php">Open</a>
</div>

<div class="card">
<h2>📝 Resignation Requests</h2>
<p>Review employee resignations.</p>
<a href="resignation_requests.php">Open</a>
</div>

<div class="card">
<h2>🎉 Holiday Management</h2>
<p>Manage company holidays.</p>
<a href="holiday_list.php">Open</a>
</div>

<div class="card">
<h2>💬 Employee Chats</h2>
<p>Chat with employees.</p>
<a href="chat_users.php">Open</a>
</div>

<div class="card">
<h2>📋 Task Management</h2>
<p>Assign and monitor tasks.</p>
<a href="task_management.php">Open</a>
</div>

<div class="card">
<h2>📊 Task Reports</h2>
<p>Track employee progress.</p>
<a href="task_report.php">Open</a>
</div>

<div class="card">
<h2>💰 Payroll Management</h2>
<p>Manage employee salaries.</p>
<a href="payroll.php">Open</a>
</div>

<div class="card">
<h2>📢 Announcements</h2>
<p>Create company announcements.</p>
<a href="announcements.php">Open</a>
</div>

<div class="card">
<h2>📈 Attendance Reports</h2>
<p>View attendance records.</p>
<a href="attendance_report.php">Open</a>
</div>

<div class="card">
<h2>🔐 Security</h2>
<p>Manage passwords and access.</p>
<a href="change_password.php">Open</a>
</div>


</div>

<div class="footer">

<br><br>

© 2025 BNY Employee Management System

</div>

</div>

</body>

</html>