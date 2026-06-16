<?php

session_start();

if(!isset($_SESSION['role']) || $_SESSION['role']!='CEO')
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$result = $conn->query(
"SELECT * FROM attendance
ORDER BY attendance_date DESC"
);

$present = $conn->query(
"SELECT COUNT(*) as total
FROM attendance
WHERE status='Present'"
);

$present_count =
$present->fetch_assoc()['total'];

$absent = $conn->query(
"SELECT COUNT(*) as total
FROM attendance
WHERE status='Absent'"
);

$absent_count =
$absent->fetch_assoc()['total'];

$total = $conn->query(
"SELECT COUNT(*) as total
FROM attendance"
);

$total_records =
$total->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance Report</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:30px;
margin:0;
}

.dashboard-btn{
display:inline-block;
padding:10px 15px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:8px;
margin-bottom:20px;
}

.dashboard-btn:hover{
background:#16a34a;
}

h1{
margin-bottom:20px;
color:#22d3ee;
}

.cards{
display:flex;
gap:20px;
margin-bottom:20px;
flex-wrap:wrap;
}

.card{
background:#1e293b;
padding:20px;
border-radius:12px;
min-width:220px;
}

.card h3{
margin:0;
color:#22d3ee;
}

.card p{
font-size:28px;
margin-top:10px;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
border-radius:12px;
overflow:hidden;
}

th,td{
padding:15px;
border:1px solid #334155;
text-align:center;
}

th{
background:#0f172a;
color:#22d3ee;
}

tr:hover{
background:#273449;
}

.present{
color:#22c55e;
font-weight:bold;
}

.absent{
color:#ef4444;
font-weight:bold;
}

</style>

</head>

<body>

<a class="dashboard-btn" href="dashboard.php">
🏠 Dashboard
</a>

<h1>📊 Attendance Report</h1>

<div class="cards">

<div class="card">
<h3>📋 Total Records</h3>
<p><?php echo $total_records; ?></p>
</div>

<div class="card">
<h3>✅ Present</h3>
<p><?php echo $present_count; ?></p>
</div>

<div class="card">
<h3>❌ Absent</h3>
<p><?php echo $absent_count; ?></p>
</div>

</div>

<table>

<tr>

<th>Employee ID</th>
<th>Name</th>
<th>Date</th>
<th>Check In</th>
<th>Check Out</th>
<th>Status</th>

</tr>

<?php

while($row = $result->fetch_assoc())
{

?>

<tr>

<td><?php echo $row['employee_id']; ?></td>

<td><?php echo $row['employee_name']; ?></td>

<td><?php echo $row['attendance_date']; ?></td>

<td><?php echo $row['check_in']; ?></td>

<td><?php echo $row['check_out']; ?></td>

<td>

<?php
if($row['status'] == 'Present')
{
    echo "<span class='present'>Present</span>";
}
else
{
    echo "<span class='absent'>Absent</span>";
}
?>

</td>

</tr>

<?php

}

?>

</table>

</body>

</html>