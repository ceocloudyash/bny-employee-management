<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];

$result = $conn->query(
"SELECT *
FROM leave_requests
WHERE employee_id='$employee_id'
ORDER BY id DESC"
);

$total = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests
WHERE employee_id='$employee_id'"
);

$total_count = $total->fetch_assoc()['total'];

$pending = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests
WHERE employee_id='$employee_id'
AND status='Pending'"
);

$pending_count = $pending->fetch_assoc()['total'];

$approved = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests
WHERE employee_id='$employee_id'
AND status='Approved'"
);

$approved_count = $approved->fetch_assoc()['total'];

$rejected = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests
WHERE employee_id='$employee_id'
AND status='Rejected'"
);

$rejected_count = $rejected->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html>

<head>

<title>My Leave Requests</title>

<style>

body{
background:#020617;
color:white;
font-family:'Segoe UI';
padding:30px;
margin:0;
}

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

h1{
margin:0;
color:#22d3ee;
}

.dashboard-btn{
padding:10px 16px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:8px;
font-weight:bold;
}

.dashboard-btn:hover{
background:#16a34a;
}

.employee-card{
background:#1e293b;
padding:20px;
border-radius:12px;
margin-bottom:20px;
}

.cards{
display:flex;
gap:20px;
flex-wrap:wrap;
margin-bottom:25px;
}

.card{
background:#1e293b;
padding:20px;
border-radius:12px;
min-width:180px;
}

.card h3{
margin:0;
color:#22d3ee;
font-size:16px;
}

.card p{
font-size:30px;
font-weight:bold;
margin-top:10px;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
border-radius:12px;
overflow:hidden;
}

th{
background:#0f172a;
color:#22d3ee;
padding:15px;
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid #334155;
}

tr:hover{
background:#273449;
}

.pending{
color:#facc15;
font-weight:bold;
}

.approved{
color:#22c55e;
font-weight:bold;
}

.rejected{
color:#ef4444;
font-weight:bold;
}

.no-data{
text-align:center;
padding:20px;
color:#94a3b8;
}

</style>

</head>

<body>

<div class="top-bar">

<h1>📝 My Leave Requests</h1>

<a
class="dashboard-btn"
href="employee_dashboard.php">

🏠 Dashboard

</a>

</div>

<div class="employee-card">

<b>Employee ID:</b>
<?php echo $employee_id; ?>

<br><br>

<b>Employee Name:</b>
<?php echo $employee_name; ?>

</div>

<div class="cards">

<div class="card">
<h3>Total Requests</h3>
<p><?php echo $total_count; ?></p>
</div>

<div class="card">
<h3>Pending</h3>
<p><?php echo $pending_count; ?></p>
</div>

<div class="card">
<h3>Approved</h3>
<p><?php echo $approved_count; ?></p>
</div>

<div class="card">
<h3>Rejected</h3>
<p><?php echo $rejected_count; ?></p>
</div>

</div>

<table>

<tr>
<th>Leave From</th>
<th>Leave To</th>
<th>Reason</th>
<th>Status</th>
</tr>

<?php

if($result && $result->num_rows > 0)
{

while($row = $result->fetch_assoc())
{

?>

<tr>

<td><?php echo $row['leave_from']; ?></td>

<td><?php echo $row['leave_to']; ?></td>

<td><?php echo $row['reason']; ?></td>

<td>

<?php

if($row['status'] == "Approved")
{
    echo "<span class='approved'>✅ Approved</span>";
}
elseif($row['status'] == "Rejected")
{
    echo "<span class='rejected'>❌ Rejected</span>";
}
else
{
    echo "<span class='pending'>⏳ Pending</span>";
}

?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="4" class="no-data">
No leave requests found
</td>

</tr>

<?php

}

?>

</table>

</body>

</html>