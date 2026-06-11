
<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$message = "";

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];

if(isset($_POST['submit']))
{
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $sql = "
    INSERT INTO leave_requests
    (
    employee_id,
    employee_name,
    leave_type,
    start_date,
    end_date,
    reason,
    status
    )
    VALUES
    (
    '$employee_id',
    '$employee_name',
    '$leave_type',
    '$start_date',
    '$end_date',
    '$reason',
    'Pending'
    )
    ";

    if($conn->query($sql))
    {
        $message = "✅ Leave Request Submitted Successfully";
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
font-family:Segoe UI;
color:white;
margin:0;
padding:30px;
}

.top-bar{
display:flex;
justify-content:flex-end;
margin-bottom:20px;
}

.dashboard-btn{
padding:10px 15px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:8px;
}

.dashboard-btn:hover{
background:#16a34a;
}

.container{
width:650px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h2{
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
select,
textarea{
width:100%;
padding:15px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
box-sizing:border-box;
}

button{
width:100%;
padding:15px;
background:#06b6d4;
border:none;
border-radius:10px;
color:white;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#0891b2;
}

</style>

</head>

<body>

<div class="top-bar">

<a class="dashboard-btn" href="employee_dashboard.php">
🏠 Dashboard
</a>

</div>

<div class="container">

<h2>📝 Apply Leave</h2>

<div class="info">
<b>Employee ID:</b>
<?php echo $employee_id; ?>
</div>

<div class="info">
<b>Employee Name:</b>
<?php echo $employee_name; ?>
</div>

<?php
if($message!="")
{
    echo "<div class='success'>$message</div>";
}
?>

<form method="POST">

<select name="leave_type" required>

<option value="">Select Leave Type</option>

<option value="Sick Leave">
Sick Leave
</option>

<option value="Casual Leave">
Casual Leave
</option>

<option value="Vacation Leave">
Vacation Leave
</option>

<option value="Emergency Leave">
Emergency Leave
</option>

</select>

<input
type="date"
name="start_date"
required>

<input
type="date"
name="end_date"
required>

<textarea
name="reason"
placeholder="Reason for Leave"
rows="5"
required></textarea>

<button
type="submit"
name="submit">

📨 Submit Leave Request

</button>

</form>

</div>

</body>

</html>
