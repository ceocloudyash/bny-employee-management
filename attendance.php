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

$date = date("Y-m-d");
$time = date("H:i:s");

$todayAttendance = $conn->query(
"SELECT * FROM attendance
WHERE employee_id='$employee_id'
AND attendance_date='$date'"
);

$today = $todayAttendance->fetch_assoc();

if(isset($_POST['checkin']))
{
    $check = $conn->query(
    "SELECT * FROM attendance
    WHERE employee_id='$employee_id'
    AND attendance_date='$date'"
    );

    if($check->num_rows == 0)
    {
        $conn->query(
        "INSERT INTO attendance
        (
        employee_id,
        employee_name,
        attendance_date,
        check_in,
        status
        )
        VALUES
        (
        '$employee_id',
        '$employee_name',
        '$date',
        '$time',
        'Present'
        )"
        );

        $message = "✅ Check In Successful";

        header("Refresh:1");
    }
    else
    {
        $message = "⚠ Already Checked In Today";
    }
}

if(isset($_POST['checkout']))
{
    $conn->query(
    "UPDATE attendance
    SET check_out='$time'
    WHERE employee_id='$employee_id'
    AND attendance_date='$date'"
    );

    $message = "✅ Check Out Successful";

    header("Refresh:1");
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance System</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:40px;
margin:0;
}

.dashboard-btn{
display:inline-block;
padding:10px 15px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:8px;
margin-bottom:20px;
}

.dashboard-btn:hover{
background:#16a34a;
}

.box{
max-width:700px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
text-align:center;
}

h1{
color:#22d3ee;
margin-bottom:20px;
}

.info{
background:#0f172a;
padding:15px;
border-radius:10px;
margin-bottom:15px;
}

button{
padding:15px 30px;
margin:10px;
border:none;
border-radius:10px;
cursor:pointer;
font-size:16px;
font-weight:bold;
}

.checkin{
background:#22c55e;
color:white;
}

.checkout{
background:#ef4444;
color:white;
}

.message{
margin:20px 0;
font-size:18px;
color:#22d3ee;
}

.status{
background:#0f172a;
padding:15px;
border-radius:10px;
margin-top:20px;
}

</style>

</head>

<body>

<a class="dashboard-btn" href="employee_dashboard.php">
🏠 Dashboard
</a>

<div class="box">

<h1>🕒 Attendance System</h1>

<div class="info">
<b>Employee:</b> <?php echo $employee_name; ?>
</div>

<div class="info">
<b>Date:</b> <?php echo $date; ?>
</div>

<div class="info">
<b>Current Time:</b> <?php echo date("h:i:s A"); ?>
</div>

<?php if(!empty($message)) { ?>

<div class="message">
<?php echo $message; ?>
</div>

<?php } ?>

<form method="POST">

<button
class="checkin"
type="submit"
name="checkin">

✅ Check In

</button>

<button
class="checkout"
type="submit"
name="checkout">

🚪 Check Out

</button>

</form>

<div class="status">

<h3>Today's Attendance</h3>

<?php

if($today)
{
    echo "<p><b>Status:</b> ".$today['status']."</p>";
    echo "<p><b>Check In:</b> ".$today['check_in']."</p>";

    if(!empty($today['check_out']))
    {
        echo "<p><b>Check Out:</b> ".$today['check_out']."</p>";
    }
}
else
{
    echo "<p>No attendance recorded today.</p>";
}

?>

</div>

</div>

</body>

</html>