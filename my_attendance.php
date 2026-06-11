<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];

$present_count = 0;
$absent_count = 0;
$total_records = 0;
$attendance_percentage = 0;

$result = $conn->query("
    SELECT *
    FROM attendance
    WHERE employee_id='$employee_id'
    ORDER BY attendance_date DESC
");

$present = $conn->query("
    SELECT COUNT(*) AS total
    FROM attendance
    WHERE employee_id='$employee_id'
    AND status='Present'
");

if ($present) {
    $present_count = $present->fetch_assoc()['total'];
}

$absent = $conn->query("
    SELECT COUNT(*) AS total
    FROM attendance
    WHERE employee_id='$employee_id'
    AND status='Absent'
");

if ($absent) {
    $absent_count = $absent->fetch_assoc()['total'];
}

$total_records = $present_count + $absent_count;

if ($total_records > 0) {
    $attendance_percentage =
    round(($present_count / $total_records) * 100, 2);
}
?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Attendance</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
color:white;
padding:30px;
margin:0;
}

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
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

h1{
margin:0;
color:#22d3ee;
}

.employee-card{
background:#1e293b;
padding:15px;
border-radius:12px;
margin-bottom:20px;
}

.cards{
display:flex;
gap:20px;
flex-wrap:wrap;
margin-bottom:20px;
}

.card{
background:#1e293b;
padding:20px;
border-radius:12px;
min-width:220px;
}

.card h3{
margin:0;
color:#22d3ee;
}

.card p{
font-size:28px;
margin-top:10px;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
}

th,td{
padding:12px;
border:1px solid #334155;
text-align:center;
}

th{
background:#0f172a;
color:#22d3ee;
}

.present{
color:#22c55e;
font-weight:bold;
}

.absent{
color:#ef4444;
font-weight:bold;
}

</style>

</head>

<body>

<div class="top-bar">

<h1>📅 My Attendance</h1>

<a href="employee_dashboard.php" class="dashboard-btn">
🏠 Dashboard
</a>

</div>

<div class="employee-card">

<b>Employee ID:</b>
<?php echo htmlspecialchars($employee_id); ?>

<br><br>

<b>Employee Name:</b>
<?php echo htmlspecialchars($employee_name); ?>

</div>

<div class="cards">

<div class="card">
<h3>📋 Total Records</h3>
<p><?php echo $total_records; ?></p>
</div>

<div class="card">
<h3>✅ Present Days</h3>
<p><?php echo $present_count; ?></p>
</div>

<div class="card">
<h3>❌ Absent Days</h3>
<p><?php echo $absent_count; ?></p>
</div>

<div class="card">
<h3>📊 Attendance %</h3>
<p><?php echo $attendance_percentage; ?>%</p>
</div>

</div>

<table>

<tr>
<th>Date</th>
<th>Check In</th>
<th>Check Out</th>
<th>Status</th>
</tr>

<?php
if ($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
?>
<tr>

<td><?php echo $row['attendance_date']; ?></td>

<td><?php echo $row['check_in']; ?></td>

<td><?php echo $row['check_out']; ?></td>

<td>
<?php
if($row['status'] == 'Present')
{
    echo "<span class='present'>✅ Present</span>";
}
else
{
    echo "<span class='absent'>❌ Absent</span>";
}
?>
</td>

</tr>
<?php
    }
}
else
{
?>
<tr>
<td colspan="4">No attendance records found.</td>
</tr>
<?php
}
?>

</table>

</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>