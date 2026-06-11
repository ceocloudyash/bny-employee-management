<?php

session_start();

include 'db.php';

$result=$conn->query(
"SELECT *
FROM holidays
ORDER BY holiday_date ASC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Holiday List</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:30px;
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
background:#ef4444;
padding:8px 12px;
color:white;
text-decoration:none;
border-radius:8px;
}

</style>

</head>

<body>

<h2>📅 Holiday Management</h2>

<p>
<a
href="add_holiday.php"
style="color:#22d3ee;">
➕ Add Holiday
</a>
</p>

<table>

<tr>

<th>Name</th>
<th>Date</th>
<th>Description</th>
<th>Action</th>

</tr>

<?php

while($row=$result->fetch_assoc())
{

?>

<tr>

<td><?php echo $row['holiday_name']; ?></td>

<td><?php echo $row['holiday_date']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>

<a
class="btn"
href="delete_holiday.php?id=<?php echo $row['id']; ?>">

Delete

</a>

</td>

</tr>

<?php

}

?>

</table>

</body>
</html>