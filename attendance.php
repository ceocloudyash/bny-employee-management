<?php

session_start();

if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$employee_id   = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];

$date = date("Y-m-d");
$time = date("H:i:s");

$message = "";
$message_type = "success";

/* FETCH TODAY ATTENDANCE */

$stmt = $conn->prepare(
    "SELECT *
     FROM attendance
     WHERE employee_id = ?
     AND attendance_date = ?"
);

$stmt->bind_param(
    "ss",
    $employee_id,
    $date
);

$stmt->execute();
$result = $stmt->get_result();
$today = $result->fetch_assoc();

/* CHECK IN */

if (isset($_POST['checkin'])) {

    if (!$today) {

        $stmt = $conn->prepare(
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
                ?, ?, ?, ?, 'Present'
            )"
        );

        $stmt->bind_param(
            "ssss",
            $employee_id,
            $employee_name,
            $date,
            $time
        );

        if ($stmt->execute()) {

            $message = "✅ Check In Successful";

            header("Refresh:1");
        } else {

            $message = "❌ Check In Failed";
            $message_type = "error";
        }

    } else {

        $message = "⚠ You have already checked in today";
        $message_type = "error";
    }
}

/* CHECK OUT */

if (isset($_POST['checkout'])) {

    if ($today) {

        if (empty($today['check_out'])) {

            $stmt = $conn->prepare(
                "UPDATE attendance
                 SET check_out=?
                 WHERE employee_id=?
                 AND attendance_date=?"
            );

            $stmt->bind_param(
                "sss",
                $time,
                $employee_id,
                $date
            );

            if ($stmt->execute()) {

                $message = "✅ Check Out Successful";

                header("Refresh:1");
            } else {

                $message = "❌ Check Out Failed";
                $message_type = "error";
            }

        } else {

            $message = "⚠ Already Checked Out";
            $message_type = "error";
        }

    } else {

        $message = "⚠ Please Check In First";
        $message_type = "error";
    }
}

/* RELOAD TODAY STATUS */

$stmt = $conn->prepare(
    "SELECT *
     FROM attendance
     WHERE employee_id = ?
     AND attendance_date = ?"
);

$stmt->bind_param(
    "ss",
    $employee_id,
    $date
);

$stmt->execute();
$result = $stmt->get_result();
$today = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

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
padding:12px 18px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:10px;
margin-bottom:20px;
}

.dashboard-btn:hover{
background:#16a34a;
}

.box{
max-width:750px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h1{
text-align:center;
color:#22d3ee;
margin-bottom:25px;
}

.info{
background:#0f172a;
padding:15px;
border-radius:10px;
margin-bottom:15px;
}

.message{
padding:15px;
border-radius:10px;
margin-bottom:20px;
font-weight:bold;
}

.success{
background:#14532d;
}

.error{
background:#7f1d1d;
}

.actions{
text-align:center;
margin-top:20px;
}

button{
padding:15px 25px;
border:none;
border-radius:10px;
font-size:16px;
font-weight:bold;
cursor:pointer;
margin:10px;
}

.checkin{
background:#22c55e;
color:white;
}

.checkout{
background:#ef4444;
color:white;
}

button:disabled{
background:#64748b;
cursor:not-allowed;
}

.status{
background:#0f172a;
padding:20px;
border-radius:12px;
margin-top:25px;
}

.status h3{
color:#22d3ee;
margin-top:0;
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
<b>Employee :</b> <?php echo htmlspecialchars($employee_name); ?>
</div>

<div class="info">
<b>Employee ID :</b> <?php echo htmlspecialchars($employee_id); ?>
</div>

<div class="info">
<b>Date :</b> <?php echo $date; ?>
</div>

<div class="info">
<b>Current Time :</b> <?php echo date("h:i:s A"); ?>
</div>

<?php if(!empty($message)){ ?>

<div class="message <?php echo $message_type; ?>">
<?php echo $message; ?>
</div>

<?php } ?>

<form method="POST">

<div class="actions">

<button
type="submit"
name="checkin"
class="checkin"
<?php if($today){ echo "disabled"; } ?>
>
✅ Check In
</button>

<button
type="submit"
name="checkout"
class="checkout"
<?php
if(!$today || !empty($today['check_out']))
{
    echo "disabled";
}
?>
>
🚪 Check Out
</button>

</div>

</form>

<div class="status">

<h3>Today's Attendance</h3>

<?php

if($today)
{
    echo "<p><b>Status:</b> ".$today['status']."</p>";

    echo "<p><b>Check In:</b> ".
    $today['check_in']."</p>";

    if(!empty($today['check_out']))
    {
        echo "<p><b>Check Out:</b> ".
        $today['check_out']."</p>";
    }
    else
    {
        echo "<p><b>Check Out:</b> Not Yet</p>";
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