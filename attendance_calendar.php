<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];

$month = date("m");
$year = date("Y");

$daysInMonth = date("t");

$present = 0;
$absent = 0;
$leave = 0;

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Attendance Calendar</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#020617;
color:white;
padding:30px;
}

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

h1{
color:#22d3ee;
}

.dashboard-btn{
background:#22c55e;
color:white;
padding:10px 15px;
text-decoration:none;
border-radius:8px;
font-weight:bold;
}

.dashboard-btn:hover{
background:#16a34a;
}

.employee-card{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
}

.summary{
display:flex;
gap:20px;
flex-wrap:wrap;
margin-bottom:25px;
}

.card{
background:#1e293b;
padding:20px;
border-radius:15px;
min-width:180px;
text-align:center;
}

.card h2{
color:#22d3ee;
margin-bottom:10px;
}

.legend{
margin-bottom:20px;
font-size:16px;
}

.legend span{
margin-right:20px;
}

.calendar{
display:grid;
grid-template-columns:repeat(7,1fr);
gap:12px;
}

.day{
padding:20px;
border-radius:12px;
text-align:center;
font-weight:bold;
min-height:100px;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
}

.present{
background:#22c55e;
}

.absent{
background:#ef4444;
}

.leave{
background:#facc15;
color:black;
}

.empty{
background:#334155;
}

.month-title{
margin-bottom:20px;
font-size:22px;
color:#22d3ee;
}

.status{
margin-top:10px;
font-size:13px;
}

@media(max-width:768px){

.calendar{
grid-template-columns:repeat(2,1fr);
}

}

</style>

</head>

<body>

<div class="top-bar">

<h1>📅 Attendance Calendar</h1>

<a
class="dashboard-btn"
href="employee_dashboard.php">

🏠 Dashboard

</a>

</div>

<div class="employee-card">

<b>Employee ID:</b>

<?php echo $employee_id; ?>

<br><br>

<b>Employee Name:</b>

<?php echo $employee_name; ?>

</div>

<div class="legend">

<span>🟢 Present</span>

<span>🔴 Absent</span>

<span>🟡 Leave</span>

</div>

<div class="month-title">

<?php echo date("F Y"); ?>

</div>

<?php

for($d=1;$d<=$daysInMonth;$d++)
{
    $date =
    $year."-".$month."-".
    str_pad($d,2,"0",STR_PAD_LEFT);

    $status = "";

    $attendance = $conn->query(
    "SELECT status
    FROM attendance
    WHERE employee_id='$employee_id'
    AND attendance_date='$date'"
    );

    if($attendance && $attendance->num_rows>0)
    {
        $row = $attendance->fetch_assoc();

        $status = $row['status'];

        if($status=="Present")
        {
            $present++;
        }
        elseif($status=="Absent")
        {
            $absent++;
        }
        else
        {
            $leave++;
        }
    }
}

?>

<div class="summary">

<div class="card">
<h2>✅ Present</h2>
<p><?php echo $present; ?></p>
</div>

<div class="card">
<h2>❌ Absent</h2>
<p><?php echo $absent; ?></p>
</div>

<div class="card">
<h2>🟡 Leave</h2>
<p><?php echo $leave; ?></p>
</div>

</div>

<div class="calendar">

<?php

for($d=1;$d<=$daysInMonth;$d++)
{

$date =
$year."-".$month."-".
str_pad($d,2,"0",STR_PAD_LEFT);

$status = "";

$result = $conn->query(
"SELECT status
FROM attendance
WHERE employee_id='$employee_id'
AND attendance_date='$date'"
);

if($result && $result->num_rows>0)
{
$row = $result->fetch_assoc();
$status = $row['status'];
}

$class = "empty";

if($status=="Present")
{
$class = "present";
}
elseif($status=="Absent")
{
$class = "absent";
}
elseif(
$status=="Leave" ||
$status=="Approved Leave"
)
{
$class = "leave";
}

?>

<div class="day <?php echo $class; ?>">

<div>

<?php echo $d; ?>

</div>

<div class="status">

<?php

if($status!="")
{
echo $status;
}
else
{
echo "-";
}

?>

</div>

</div>

<?php

}

?>

</div>

</body>

</html>
