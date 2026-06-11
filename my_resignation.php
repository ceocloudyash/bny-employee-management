<?php

session_start();

include 'db.php';

$employee_id=$_SESSION['employee_id'];

$result=$conn->query(
"SELECT *
FROM resignations
WHERE employee_id='$employee_id'
ORDER BY id DESC"
);

?>

<!DOCTYPE html>

<html>
<head>

<title>My Resignation</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:20px;
}

table{
width:100%;
background:#1e293b;
border-collapse:collapse;
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

<h1>📋 My Resignation Requests</h1>

<table>

<tr>
<th>ID</th>
<th>Reason</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php

while($row=$result->fetch_assoc())
{
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['reason']; ?></td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['applied_on']; ?></td>

</tr>

<?php
}
?>

</table>

</body>
</html>
