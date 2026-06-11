<?php

session_start();

if(
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'EMPLOYEE'
)
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];

$result = $conn->query(
"SELECT *
FROM payroll
WHERE employee_id='$employee_id'
ORDER BY id DESC"
);

$totalSalary = 0;

$total = $conn->query(
"SELECT SUM(net_salary) AS total
FROM payroll
WHERE employee_id='$employee_id'"
);

if($total)
{
    $rowTotal = $total->fetch_assoc();
    $totalSalary = $rowTotal['total'] ? $rowTotal['total'] : 0;
}

?>

<!DOCTYPE html>
<html>

<head>

<title>My Payroll</title>

<style>

body{
background:#020617;
font-family:'Segoe UI';
color:white;
padding:30px;
margin:0;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

h1{
color:#22d3ee;
margin:0;
}

.dashboard-btn{
background:#22c55e;
padding:10px 15px;
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
border-radius:15px;
margin-bottom:20px;
}

.summary{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
}

.summary h2{
color:#22d3ee;
margin:0;
}

.summary p{
font-size:28px;
font-weight:bold;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
border-radius:15px;
overflow:hidden;
}

th{
background:#0f172a;
color:#22d3ee;
padding:15px;
}

td{
padding:15px;
border-bottom:1px solid #334155;
}

tr:hover{
background:#273449;
}

.no-data{
text-align:center;
padding:20px;
color:#94a3b8;
}

</style>

</head>

<body>

<div class="topbar">

<h1>💰 My Payroll</h1>

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

<div class="summary">

<h2>Total Salary Received</h2>

<p>
₹<?php echo number_format($totalSalary); ?>
</p>

</div>

<table>

<tr>

<th>ID</th>
<th>Basic Salary</th>
<th>Bonus</th>
<th>Deductions</th>
<th>Net Salary</th>

</tr>

<?php

if($result && $result->num_rows > 0)
{

while($row = $result->fetch_assoc())
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
₹<?php echo number_format($row['basic_salary']); ?>
</td>

<td>
₹<?php echo number_format($row['bonus']); ?>
</td>

<td>
₹<?php echo number_format($row['deductions']); ?>
</td>

<td>
₹<?php echo number_format($row['net_salary']); ?>
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

No Payroll Records Found

</td>

</tr>

<?php

}

?>

</table>

</body>

</html>