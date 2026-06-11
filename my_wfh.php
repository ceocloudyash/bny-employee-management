<?php

session_start();

if(
!isset($_SESSION['employee_id'])
||
$_SESSION['role']!='EMPLOYEE'
)
{
header("Location:index.php");
exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];

$result = $conn->query(
"SELECT *
FROM wfh_requests
WHERE employee_id='$employee_id'
ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>My WFH Requests</title>

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
padding:30px;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

h2{
color:#22d3ee;
}

.dashboard-btn{
background:#22c55e;
color:white;
text-decoration:none;
padding:12px 18px;
border-radius:10px;
font-weight:bold;
transition:.3s;
}

.dashboard-btn:hover{
background:#16a34a;
}

.container{
background:#1e293b;
padding:20px;
border-radius:15px;
overflow-x:auto;
box-shadow:0 0 20px rgba(0,0,0,.3);
}

table{
width:100%;
border-collapse:collapse;
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

.status-pending{
color:#facc15;
font-weight:bold;
}

.status-approved{
color:#22c55e;
font-weight:bold;
}

.status-rejected{
color:#ef4444;
font-weight:bold;
}

.no-data{
text-align:center;
padding:25px;
color:#94a3b8;
}

</style>

</head>

<body>

<div class="topbar">

<h2>🏠 My Work From Home Requests</h2>

<a
href="employee_dashboard.php"
class="dashboard-btn">

🏠 Dashboard

</a>

</div>

<div class="container">

<table>

<tr>

<th>ID</th>
<th>From Date</th>
<th>To Date</th>
<th>Reason</th>
<th>Status</th>

</tr>

<?php

if($result && $result->num_rows > 0)
{

while($row = $result->fetch_assoc())
{

$statusClass = "";

if($row['status']=="Pending")
{
$statusClass="status-pending";
}
elseif($row['status']=="Approved")
{
$statusClass="status-approved";
}
elseif($row['status']=="Rejected")
{
$statusClass="status-rejected";
}

?>

<tr>

<td>
<?php echo $row['id']; ?>
</td>

<td>
<?php echo $row['from_date']; ?>
</td>

<td>
<?php echo $row['to_date']; ?>
</td>

<td>
<?php echo $row['reason']; ?>
</td>

<td class="<?php echo $statusClass; ?>">
<?php echo $row['status']; ?>
</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="5" class="no-data">

No WFH requests found.

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>