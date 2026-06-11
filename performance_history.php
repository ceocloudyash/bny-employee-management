<?php

ob_start();
session_start();

if(!isset($_SESSION['role']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

/* GET EMPLOYEE ID */

if(isset($_GET['id']))
{
    $employee_id = $_GET['id'];
}
elseif(
    $_SESSION['role']=='EMPLOYEE'
    &&
    isset($_SESSION['employee_id'])
)
{
    $employee_id = $_SESSION['employee_id'];
}
else
{
    die("Employee ID Not Found");
}

/* GET EMPLOYEE DETAILS */

$employee_name = "";

$stmt = $conn->prepare(
"SELECT name
FROM employees
WHERE employee_id=?"
);

$stmt->bind_param(
"s",
$employee_id
);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0)
{
    $emp = $result->fetch_assoc();
    $employee_name = $emp['name'];
}

/* ADD REVIEW (CEO ONLY) */

$message = "";

if(
isset($_POST['save_review'])
&&
$_SESSION['role']=='CEO'
)
{
    $review_date = $_POST['review_date'];
    $rating = $_POST['rating'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare(
    "INSERT INTO performance_history
    (
        employee_id,
        employee_name,
        review_date,
        rating,
        remarks
    )
    VALUES
    (
        ?,?,?,?,?
    )"
    );

    $stmt->bind_param(
    "sssss",
    $employee_id,
    $employee_name,
    $review_date,
    $rating,
    $remarks
    );

    if($stmt->execute())
    {
        $message =
        "✅ Performance Review Added";
    }
    else
    {
        $message =
        "❌ Failed To Save Review";
    }
}

/* FETCH REVIEWS */

$stmt = $conn->prepare(
"SELECT *
FROM performance_history
WHERE employee_id=?
ORDER BY review_date DESC"
);

$stmt->bind_param(
"s",
$employee_id
);

$stmt->execute();

$reviews = $stmt->get_result();

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

.card{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
}

input,
select,
textarea{
width:100%;
padding:12px;
margin:10px 0;
border:none;
border-radius:8px;
box-sizing:border-box;
}

button{
padding:12px 20px;
background:#06b6d4;
color:white;
border:none;
border-radius:8px;
cursor:pointer;
}

button:hover{
background:#0891b2;
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

.success{
background:#14532d;
padding:12px;
border-radius:8px;
margin-bottom:15px;
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

.excellent{
color:#22c55e;
font-weight:bold;
}

.good{
color:#facc15;
font-weight:bold;
}

.average{
color:#fb923c;
font-weight:bold;
}

.poor{
color:#ef4444;
font-weight:bold;
}

</style>

</head>

<body>

<h1>📈 Performance History</h1>

<div class="card">

<b>Employee ID:</b>
<?php echo htmlspecialchars($employee_id); ?>

<br><br>

<b>Employee Name:</b>
<?php echo htmlspecialchars($employee_name); ?>

</div>

<?php
if($message!="")
{
echo "<div class='success'>$message</div>";
}
?>

<?php if($_SESSION['role']=="CEO"){ ?>

<div class="card">

<h3>Add Performance Review</h3>

<form method="POST">

<input
type="date"
name="review_date"
required>

<select
name="rating"
required>

<option value="">
Select Rating
</option>

<option value="Excellent">
Excellent
</option>

<option value="Good">
Good
</option>

<option value="Average">
Average
</option>

<option value="Poor">
Poor
</option>

</select>

<textarea
name="remarks"
rows="5"
placeholder="Remarks"
required></textarea>

<button
type="submit"
name="save_review">

💾 Save Review

</button>

</form>

</div>

<?php } ?>

<table>

<tr>

<th>Date</th>
<th>Rating</th>
<th>Remarks</th>

</tr>

<?php

if($reviews->num_rows > 0)
{

while($row = $reviews->fetch_assoc())
{

?>

<tr>

<td>
<?php echo $row['review_date']; ?>
</td>

<td>

<?php

$class = strtolower($row['rating']);

echo
"<span class='$class'>".
htmlspecialchars($row['rating']).
"</span>";

?>

</td>

<td>
<?php echo htmlspecialchars($row['remarks']); ?>
</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="3" style="text-align:center;">
No Performance Reviews Found
</td>

</tr>

<?php

}

?>

</table>

<br>

<?php if($_SESSION['role']=="CEO"){ ?>

<a
class="back"
href="employees.php">

⬅ Back To Employees

</a>

<?php } else { ?>

<a
class="back"
href="employee_dashboard.php">

⬅ Back To Dashboard

</a>

<?php } ?>

</body>

</html>