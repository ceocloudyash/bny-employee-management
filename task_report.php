<?php

session_start();

if(
!isset($_SESSION['role'])
||
$_SESSION['role']!='CEO'
)
{
header("Location:index.php");
exit();
}

include 'db.php';

$result=$conn->query(
"SELECT *
FROM tasks
ORDER BY id DESC"
);

$total=$conn->query(
"SELECT COUNT(*) as total
FROM tasks"
);

$total_tasks=$total->fetch_assoc()['total'];

$pending=$conn->query(
"SELECT COUNT(*) as total
FROM tasks
WHERE status='Pending'"
);

$pending_tasks=$pending->fetch_assoc()['total'];

$progress=$conn->query(
"SELECT COUNT(*) as total
FROM tasks
WHERE status='In Progress'"
);

$progress_tasks=$progress->fetch_assoc()['total'];

$completed=$conn->query(
"SELECT COUNT(*) as total
FROM tasks
WHERE status='Completed'"
);

$completed_tasks=$completed->fetch_assoc()['total'];

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<title>Task Reports</title>

<style>

body{
background:#020617;
font-family:'Segoe UI';
color:white;
padding:30px;
margin:0;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

h1{
color:#22d3ee;
margin:0;
}

.dashboard-btn{
background:#22c55e;
color:white;
padding:12px 18px;
text-decoration:none;
border-radius:10px;
font-weight:bold;
}

.dashboard-btn:hover{
background:#16a34a;
}

.stats{
display:grid;
grid-template-columns:
repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:25px;
}

.card{
background:#1e293b;
padding:20px;
border-radius:15px;
text-align:center;
box-shadow:0 0 15px rgba(0,0,0,.3);
}

.card h2{
font-size:35px;
margin-bottom:10px;
color:#22d3ee;
}

.search{
width:100%;
padding:14px;
border:none;
border-radius:10px;
background:#1e293b;
color:white;
margin-bottom:20px;
font-size:15px;
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
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid #334155;
}

tr:hover{
background:#273449;
}

.pending{
color:#facc15;
font-weight:bold;
}

.progress{
color:#38bdf8;
font-weight:bold;
}

.completed{
color:#22c55e;
font-weight:bold;
}

.overdue{
color:#ef4444;
font-weight:bold;
}

</style>

<script>

function searchTask()
{
let input=
document.getElementById("search");

let filter=
input.value.toUpperCase();

let table=
document.getElementById("taskTable");

let tr=
table.getElementsByTagName("tr");

for(let i=1;i<tr.length;i++)
{
let td=
tr[i].getElementsByTagName("td")[1];

if(td)
{
let txt=
td.textContent||td.innerText;

if(txt.toUpperCase().indexOf(filter)>-1)
{
tr[i].style.display="";
}
else
{
tr[i].style.display="none";
}
}
}
}

</script>

</head>

<body>

<div class="topbar">

<h1>📊 Task Reports</h1>

<a
class="dashboard-btn"
href="dashboard.php">

🏠 Dashboard

</a>

</div>

<div class="stats">

<div class="card">
<h2><?php echo $total_tasks; ?></h2>
<p>Total Tasks</p>
</div>

<div class="card">
<h2><?php echo $pending_tasks; ?></h2>
<p>Pending</p>
</div>

<div class="card">
<h2><?php echo $progress_tasks; ?></h2>
<p>In Progress</p>
</div>

<div class="card">
<h2><?php echo $completed_tasks; ?></h2>
<p>Completed</p>
</div>

</div>

<input
type="text"
id="search"
class="search"
onkeyup="searchTask()"
placeholder="🔍 Search Employee Name">

<table id="taskTable">

<tr>

<th>ID</th>
<th>Employee</th>
<th>Task Title</th>
<th>Description</th>
<th>Deadline</th>
<th>Status</th>

</tr>

<?php

while($row=$result->fetch_assoc())
{

?>

<tr>

<td>
<?php echo $row['id']; ?>
</td>

<td>
<?php echo $row['employee_name']; ?>
</td>

<td>
<?php echo $row['task_title']; ?>
</td>

<td>
<?php echo $row['task_description']; ?>
</td>

<td>

<?php

echo $row['deadline'];

if(
strtotime($row['deadline']) < time()
&&
$row['status']!='Completed'
)
{
echo "<br><span class='overdue'>⚠ Overdue</span>";
}

?>

</td>

<td>

<?php

if($row['status']=="Pending")
{
echo "<span class='pending'>🟡 Pending</span>";
}
elseif($row['status']=="In Progress")
{
echo "<span class='progress'>🔵 In Progress</span>";
}
else
{
echo "<span class='completed'>🟢 Completed</span>";
}

?>

</td>

</tr>

<?php

}

?>

</table>

</body>

</html>
