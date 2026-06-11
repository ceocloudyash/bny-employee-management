
<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$msg = "";

if(isset($_POST['apply']))
{
    $employee_id = $_SESSION['employee_id'];
    $employee_name = $_SESSION['employee_name'];

    $leave_from = $_POST['leave_from'];
    $leave_to = $_POST['leave_to'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO leave_requests
    (
    employee_id,
    employee_name,
    leave_from,
    leave_to,
    reason
    )
    VALUES
    (
    '$employee_id',
    '$employee_name',
    '$leave_from',
    '$leave_to',
    '$reason'
    )";

    if($conn->query($sql))
    {
        $msg = "✅ Leave Applied Successfully";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Apply Leave</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:30px;
margin:0;
}

.top-bar{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.dashboard-btn,
.back-btn{
padding:10px 15px;
text-decoration:none;
color:white;
border-radius:8px;
}

.dashboard-btn{
background:#22c55e;
}

.dashboard-btn:hover{
background:#16a34a;
}

.back-btn{
background:#06b6d4;
}

.back-btn:hover{
background:#0891b2;
}

.box{
max-width:700px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h1{
color:#22d3ee;
margin-top:0;
}

.info{
background:#0f172a;
padding:12px;
border-radius:10px;
margin-bottom:10px;
}

.success{
background:#14532d;
padding:12px;
border-radius:10px;
margin-bottom:15px;
}

input,
textarea{
width:100%;
padding:12px;
margin-bottom:15px;
background:#0f172a;
border:none;
color:white;
border-radius:10px;
box-sizing:border-box;
}

label{
display:block;
margin-bottom:8px;
}

button{
width:100%;
padding:15px;
background:#22d3ee;
border:none;
color:black;
font-weight:bold;
border-radius:10px;
cursor:pointer;
font-size:16px;
}

button:hover{
background:#06b6d4;
}

</style>

</head>

<body>

<div class="top-bar">

<a class="back-btn" href="employee_dashboard.php">
⬅ Back
</a>

<a class="dashboard-btn" href="employee_dashboard.php">
🏠 Dashboard
</a>

</div>

<div class="box">

<h1>📝 Apply Leave</h1>

<div class="info">
<b>Employee ID:</b>
<?php echo $_SESSION['employee_id']; ?>
</div>

<div class="info">
<b>Employee Name:</b>
<?php echo $_SESSION['employee_name']; ?>
</div>

<?php
if($msg != "")
{
    echo "<div class='success'>$msg</div>";
}
?>

<form method="POST">

<label>Leave From</label>

<input
type="date"
name="leave_from"
required>

<label>Leave To</label>

<input
type="date"
name="leave_to"
required>

<label>Reason</label>

<textarea
name="reason"
rows="5"
required></textarea>

<button
type="submit"
name="apply">

📨 Apply Leave

</button>

</form>

</div>

</body>

</html>

