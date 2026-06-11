<?php

session_start();

if($_SESSION['role']!='CEO')
{
header("Location:index.php");
exit();
}

include 'db.php';

$result=$conn->query(
"SELECT *
FROM resignations
ORDER BY id DESC"
);

?>

<!DOCTYPE html>

<html>
<head>

<title>Resignation Requests</title>

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

.btn{
padding:8px 12px;
text-decoration:none;
color:white;
border-radius:8px;
}

.approve{
background:#22c55e;
}

.reject{
background:#ef4444;
}

</style>

</head>

<body>

<h1>📝 Resignation Requests</h1>

<table>

<tr>

<th>ID</th>
<th>Employee</th>
<th>Reason</th>
<th>Status</th>
<th>Action</th>

</tr>

<?php

while($row=$result->fetch_assoc())
{
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<?php echo $row['employee_name']; ?>
</td>

<td>
<?php echo $row['reason']; ?>
</td>

<td>
<?php echo $row['status']; ?>
</td>

<td>

<a
class="btn approve"
href="resignation_action.php?id=<?php echo $row['id']; ?>&status=Approved">

Approve

</a>

<a
class="btn reject"
href="resignation_action.php?id=<?php echo $row['id']; ?>&status=Rejected">

Reject

</a>

</td>

</tr>

<?php
}
?>

</table>

</body>
</html>
