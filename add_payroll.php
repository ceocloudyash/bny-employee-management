<?php

session_start();

if(!isset($_SESSION['role']) || $_SESSION['role']!='CEO')
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$message="";

if(isset($_POST['save']))
{
    $employee_id=$_POST['employee_id'];
    $employee_name=$_POST['employee_name'];
    $month=$_POST['month'];
    $basic_salary=$_POST['basic_salary'];
    $bonus=$_POST['bonus'];
    $deductions=$_POST['deductions'];

    $net_salary=$basic_salary+$bonus-$deductions;

    $sql="INSERT INTO payroll
    (
    employee_id,
    employee_name,
    month,
    basic_salary,
    bonus,
    deductions,
    net_salary
    )

    VALUES

    (
    '$employee_id',
    '$employee_name',
    '$month',
    '$basic_salary',
    '$bonus',
    '$deductions',
    '$net_salary'
    )";

    if($conn->query($sql))
    {
        $message="Payroll Added Successfully";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Payroll</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:30px;
}

.container{

max-width:700px;

margin:auto;

background:#1e293b;

padding:30px;

border-radius:20px;

}

input{

width:100%;

padding:15px;

margin-bottom:15px;

border:none;

border-radius:10px;

background:#0f172a;

color:white;

}

button{

width:100%;

padding:15px;

background:#22d3ee;

border:none;

border-radius:10px;

font-size:16px;

cursor:pointer;

}

.success{
color:lime;
margin-bottom:15px;
}

</style>

</head>

<body>

<div class="container">

<h1>💰 Add Payroll</h1>

<div class="success">
<?php echo $message; ?>
</div>

<form method="POST">

<input type="text"
name="employee_id"
placeholder="Employee ID"
required>

<input type="text"
name="employee_name"
placeholder="Employee Name"
required>

<input type="text"
name="month"
placeholder="Month (June 2025)"
required>

<input type="number"
name="basic_salary"
placeholder="Basic Salary"
required>

<input type="number"
name="bonus"
placeholder="Bonus"
required>

<input type="number"
name="deductions"
placeholder="Deductions"
required>

<button type="submit" name="save">

Save Payroll

</button>

</form>

</div>

</body>

</html>