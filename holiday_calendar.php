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

<title>Holiday Calendar</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:30px;
}

.card{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:15px;
border-left:5px solid #22d3ee;
}

.date{
color:#22d3ee;
font-weight:bold;
font-size:18px;
}

</style>

</head>

<body>

<h2>📅 Company Holiday Calendar</h2>

<br>

<?php

if($result->num_rows>0)
{

while($row=$result->fetch_assoc())
{

?>

<div class="card">

<div class="date">

<?php echo date("d M Y",strtotime($row['holiday_date'])); ?>

</div>

<h3>

<?php echo $row['holiday_name']; ?>

</h3>

<p>

<?php echo $row['description']; ?>

</p>

</div>

<?php

}

}
else
{

echo "<h3>No Holidays Added</h3>";

}

?>

</body>
</html>