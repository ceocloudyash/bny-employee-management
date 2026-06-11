<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION['username']) ||
    $_SESSION['role'] != 'CEO'
) {
    header("Location: index.php");
    exit();
}

include 'db.php';

/* FETCH LEAVE REQUESTS */

$result = $conn->query("
    SELECT *
    FROM leave_requests
    ORDER BY id DESC
");

/* TOTAL */

$total_result = $conn->query("
    SELECT COUNT(*) AS total
    FROM leave_requests
");

$total_count = 0;

if ($total_result) {
    $total_count = $total_result->fetch_assoc()['total'];
}

/* PENDING */

$pending_result = $conn->query("
    SELECT COUNT(*) AS total
    FROM leave_requests
    WHERE status='Pending'
");

$pending_count = 0;

if ($pending_result) {
    $pending_count = $pending_result->fetch_assoc()['total'];
}

/* APPROVED */

$approved_result = $conn->query("
    SELECT COUNT(*) AS total
    FROM leave_requests
    WHERE status='Approved'
");

$approved_count = 0;

if ($approved_result) {
    $approved_count = $approved_result->fetch_assoc()['total'];
}

/* REJECTED */

$rejected_result = $conn->query("
    SELECT COUNT(*) AS total
    FROM leave_requests
    WHERE status='Rejected'
");

$rejected_count = 0;

if ($rejected_result) {
    $rejected_count = $rejected_result->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Leave Requests</title>

<style>

body{
background:#020617;
color:white;
font-family:'Segoe UI',sans-serif;
padding:30px;
margin:0;
}

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

h1{
margin:0;
color:#22d3ee;
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

.cards{
display:flex;
gap:20px;
flex-wrap:wrap;
margin-bottom:25px;
}

.card{
background:#1e293b;
padding:20px;
border-radius:12px;
min-width:200px;
box-shadow:0 0 15px rgba(0,0,0,.2);
}

.card h3{
margin:0;
color:#22d3ee;
}

.card p{
font-size:28px;
margin-top:10px;
font-weight:bold;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
border-radius:12px;
overflow:hidden;
}

th,td{
padding:15px;
border:1px solid #334155;
text-align:left;
}

th{
background:#0f172a;
color:#22d3ee;
}

tr:hover{
background:#273449;
}

.pending{
color:#facc15;
font-weight:bold;
}

.approved{
color:#22c55e;
font-weight:bold;
}

.rejected{
color:#ef4444;
font-weight:bold;
}

.approve{
background:#22c55e;
padding:8px 12px;
border-radius:8px;
text-decoration:none;
color:white;
margin-right:5px;
}

.reject{
background:#ef4444;
padding:8px 12px;
border-radius:8px;
text-decoration:none;
color:white;
}

.approve:hover{
background:#16a34a;
}

.reject:hover{
background:#dc2626;
}

</style>

</head>

<body>

<div class="top-bar">

<h1>📝 Leave Requests</h1>

<a href="dashboard.php" class="dashboard-btn">
🏠 Dashboard
</a>

</div>

<div class="cards">

<div class="card">
<h3>Total Requests</h3>
<p><?php echo $total_count; ?></p>
</div>

<div class="card">
<h3>Pending</h3>
<p><?php echo $pending_count; ?></p>
</div>

<div class="card">
<h3>Approved</h3>
<p><?php echo $approved_count; ?></p>
</div>

<div class="card">
<h3>Rejected</h3>
<p><?php echo $rejected_count; ?></p>
</div>

</div>

<table>

<tr>
<th>Employee</th>
<th>From</th>
<th>To</th>
<th>Reason</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
if ($result && $result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
?>
<tr>

<td><?php echo htmlspecialchars($row['employee_name']); ?></td>

<td><?php echo htmlspecialchars($row['leave_from']); ?></td>

<td><?php echo htmlspecialchars($row['leave_to']); ?></td>

<td><?php echo htmlspecialchars($row['reason']); ?></td>

<td>

<?php

if($row['status'] == 'Approved')
{
    echo "<span class='approved'>Approved</span>";
}
elseif($row['status'] == 'Rejected')
{
    echo "<span class='rejected'>Rejected</span>";
}
else
{
    echo "<span class='pending'>Pending</span>";
}

?>

</td>

<td>

<?php if($row['status'] == 'Pending') { ?>

<a
class="approve"
href="approve_leave.php?id=<?php echo $row['id']; ?>">
✅ Approve
</a>

<a
class="reject"
href="reject_leave.php?id=<?php echo $row['id']; ?>">
❌ Reject
</a>

<?php } else { ?>

—

<?php } ?>

</td>

</tr>

<?php
    }
}
else
{
?>

<tr>
<td colspan="6">
No Leave Requests Found
</td>
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