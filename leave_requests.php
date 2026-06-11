
<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$result = $conn->query(
"SELECT * FROM leave_requests
ORDER BY id DESC"
);

$total = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests"
);

$total_count =
$total->fetch_assoc()['total'];

$pending = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests
WHERE status='Pending'"
);

$pending_count =
$pending->fetch_assoc()['total'];

$approved = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests
WHERE status='Approved'"
);

$approved_count =
$approved->fetch_assoc()['total'];

$rejected = $conn->query(
"SELECT COUNT(*) AS total
FROM leave_requests
WHERE status='Rejected'"
);

$rejected_count =
$rejected->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html>

<head>

<title>Leave Requests</title>

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
align-items:center;
margin-bottom:20px;
}

h1{
color:#22d3ee;
margin:0;
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
margin-bottom:25px;
flex-wrap:wrap;
}

.card{
background:#1e293b;
padding:20px;
border-radius:12px;
min-width:180px;
}

.card h3{
margin:0;
color:#22d3ee;
}

.card p{
font-size:28px;
margin:10px 0 0;
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

.approve{
background:#22c55e;
padding:8px 12px;
text-decoration:none;
border-radius:8px;
color:white;
}

.reject{
background:#ef4444;
padding:8px 12px;
text-decoration:none;
border-radius:8px;
color:white;
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

</style>

</head>

<body>

<div class="top-bar">

<h1>📝 Leave Requests</h1>

<a class="dashboard-btn" href="dashboard.php">
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

while($row = $result->fetch_assoc())
{

?>

<tr>

<td><?php echo $row['employee_name']; ?></td>

<td><?php echo $row['leave_from']; ?></td>

<td><?php echo $row['leave_to']; ?></td>

<td><?php echo $row['reason']; ?></td>

<td>

<?php

if($row['status']=="Approved")
{
    echo "<span class='approved'>Approved</span>";
}
elseif($row['status']=="Rejected")
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

</td>

</tr>

<?php

}

?>

</table>

</body>

</html>

