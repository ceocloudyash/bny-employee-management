<?php

session_start();

if(
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'CEO'
)
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$message = "";

if(isset($_POST['save']))
{
    $employee_id = $_POST['employee_id'];

    $employee = $conn->query(
    "SELECT *
    FROM employees
    WHERE employee_id='$employee_id'"
    );

    if($employee->num_rows > 0)
    {
        $emp = $employee->fetch_assoc();

        $employee_name = $emp['name'];

        $basic_salary = $_POST['basic_salary'];
        $bonus = $_POST['bonus'];
        $deductions = $_POST['deductions'];

        $net_salary =
        $basic_salary +
        $bonus -
        $deductions;

        $sql = "

        INSERT INTO payroll

        (
        employee_id,
        employee_name,
        basic_salary,
        bonus,
        deductions,
        net_salary
        )

        VALUES

        (
        '$employee_id',
        '$employee_name',
        '$basic_salary',
        '$bonus',
        '$deductions',
        '$net_salary'
        )

        ";

        if($conn->query($sql))
        {
            $message =
            "Payroll Saved Successfully";
        }
        else
        {
            $message =
            "Database Error";
        }
    }
}

$employees = $conn->query(
"SELECT employee_id,name
FROM employees
ORDER BY name ASC"
);

$payrolls = $conn->query(
"SELECT *
FROM payroll
ORDER BY id DESC
LIMIT 20"
);
$conn->query(
"INSERT INTO notifications
(
employee_id,
title,
message
)
VALUES
(
'$employee_id',
'Payroll Added',
'Your salary record has been added.'
)"
);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>Payroll Management</title>

<style>

body{
background:#020617;
font-family:'Segoe UI';
color:white;
margin:0;
padding:25px;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.title{
font-size:32px;
font-weight:bold;
color:#22d3ee;
}

.dashboard-btn{
background:#22c55e;
color:white;
padding:12px 18px;
border-radius:10px;
text-decoration:none;
font-weight:bold;
}

.dashboard-btn:hover{
background:#16a34a;
}

.container{
background:#1e293b;
padding:25px;
border-radius:20px;
margin-bottom:25px;
}

.success{
background:#14532d;
padding:15px;
border-radius:10px;
margin-bottom:15px;
color:#86efac;
}

select,
input{
width:100%;
padding:15px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
font-size:15px;
}

button{
width:100%;
padding:15px;
background:#06b6d4;
border:none;
border-radius:10px;
font-size:16px;
color:white;
cursor:pointer;
font-weight:bold;
}

button:hover{
background:#0891b2;
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

.heading{
color:#22d3ee;
margin-bottom:15px;
}

</style>

</head>

<body>

<div class="topbar">

<div class="title">
💰 Payroll Management
</div>

<a
href="dashboard.php"
class="dashboard-btn">

🏠 Dashboard

</a>

</div>

<div class="container">

<h2 class="heading">
Create Payroll
</h2>

<?php

if($message!="")
{
echo "<div class='success'>$message</div>";
}

?>

<form method="POST">

<select
name="employee_id"
required>

<option value="">
Select Employee
</option>

<?php

while($row=$employees->fetch_assoc())
{

?>

<option
value="<?php echo $row['employee_id']; ?>">

<?php

echo $row['employee_id'];
echo " - ";
echo $row['name'];

?>

</option>

<?php

}

?>

</select>

<input
type="number"
name="basic_salary"
placeholder="Basic Salary"
required>

<input
type="number"
name="bonus"
placeholder="Bonus"
value="0"
required>

<input
type="number"
name="deductions"
placeholder="Deductions"
value="0"
required>

<button
type="submit"
name="save">

💾 Save Payroll

</button>

</form>

</div>

<div class="container">

<h2 class="heading">
Recent Payroll Records
</h2>

<table>

<tr>

<th>ID</th>
<th>Employee ID</th>
<th>Name</th>
<th>Basic</th>
<th>Bonus</th>
<th>Deductions</th>
<th>Net Salary</th>

</tr>

<?php

if($payrolls->num_rows > 0)
{

while($row=$payrolls->fetch_assoc())
{

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['employee_id']; ?></td>

<td><?php echo $row['employee_name']; ?></td>

<td>₹<?php echo $row['basic_salary']; ?></td>

<td>₹<?php echo $row['bonus']; ?></td>

<td>₹<?php echo $row['deductions']; ?></td>

<td>₹<?php echo $row['net_salary']; ?></td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="7">

No Payroll Records Found

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>
