
<?php

session_start();

if(!isset($_SESSION['role']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

if(!isset($_GET['id']))
{
    die("Employee ID Missing");
}

$employee_id = $_GET['id'];

$result = $conn->query(
"SELECT * FROM employee_performance
WHERE employee_id='$employee_id'
ORDER BY review_date DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Performance History</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:20px;
margin:0;
}

h1{
color:#22d3ee;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
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

.back{
display:inline-block;
margin-top:20px;
padding:10px 15px;
background:#06b6d4;
color:white;
text-decoration:none;
border-radius:8px;
}

</style>

</head>

<body>

<h1>📊 Performance History</h1>

<table>

<tr>

<th>Review Date</th>
<th>Rating</th>
<th>Feedback</th>
<th>Reviewer</th>

</tr>

<?php

if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
?>

<tr>

<td><?php echo $row['review_date']; ?></td>

<td>
⭐ <?php echo $row['rating']; ?>/5
</td>

<td><?php echo $row['feedback']; ?></td>

<td><?php echo $row['reviewer']; ?></td>

</tr>

<?php

    }
}
else
{

?>

<tr>

<td colspan="4">
No Performance Reviews Found
</td>

</tr>

<?php

}

?>

</table>

<br>

<a class="back" href="employees.php">
⬅ Back to Employees
</a>

</body>

</html>

