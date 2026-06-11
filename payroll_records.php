<?php

session_start();

include 'db.php';

$result =
$conn->query(
"SELECT * FROM payroll ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Payroll Records</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:30px;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
}

th,td{
padding:15px;
border:1px solid #334155;
}

th{
background:#0f172a;
color:#22d3ee;
}

</style>

</head>

<body>

<h1>💰 Payroll Records</h1>

<table>

<tr>

<th>ID</th>
<th>Employee</th>
<th>Basic Salary</th>
<th>Bonus</th>
<th>Deductions</th>
<th>Net Salary</th>

</tr>

<?php

while($row = $result->fetch_assoc())
{

?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['employee_name']; ?></td>
<td><?php echo $row['basic_salary']; ?></td>
<td><?php echo $row['bonus']; ?></td>
<td><?php echo $row['deductions']; ?></td>
<td><?php echo $row['net_salary']; ?></td>

</tr>

<?php

}

?>

</table>

</body>

</html>