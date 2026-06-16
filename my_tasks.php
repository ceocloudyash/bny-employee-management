<?php

session_start();

if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];

$stmt = $conn->prepare("
    SELECT *
    FROM tasks
    WHERE employee_id = ?
    ORDER BY id DESC
");

$stmt->bind_param("s", $employee_id);
$stmt->execute();

$result = $stmt->get_result();

$total_tasks = $result->num_rows;

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Tasks</title>

<style>

body{
background:#020617;
font-family:'Segoe UI',sans-serif;
color:white;
padding:30px;
margin:0;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
flex-wrap:wrap;
gap:15px;
margin-bottom:25px;
}

h1{
color:#22d3ee;
margin:0;
}

.task-count{
background:#1e293b;
padding:10px 15px;
border-radius:10px;
font-weight:bold;
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

.task-container{
max-width:1000px;
margin:auto;
}

.task{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
box-shadow:0 0 15px rgba(0,0,0,.3);
transition:.3s;
}

.task:hover{
transform:translateY(-3px);
}

.task h3{
color:#22d3ee;
margin-bottom:10px;
}

.task p{
margin:8px 0;
line-height:1.6;
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

.update-btn{
display:inline-block;
margin-top:15px;
padding:10px 15px;
background:#06b6d4;
color:white;
text-decoration:none;
border-radius:8px;
font-weight:bold;
}

.update-btn:hover{
background:#0891b2;
}

.no-task{
background:#1e293b;
padding:30px;
border-radius:15px;
text-align:center;
font-size:18px;
}

</style>

</head>

<body>

<div class="task-container">

<div class="topbar">

<h1>📋 My Tasks</h1>

<div class="task-count">
Total Tasks: <?php echo $total_tasks; ?>
</div>

<a
class="dashboard-btn"
href="employee_dashboard.php">
🏠 Dashboard </a>

</div>

<?php

if ($result && $result->num_rows > 0) {

while ($row = $result->fetch_assoc()) {

?>

<div class="task">

<h3>
<?php echo htmlspecialchars($row['task_title']); ?>
</h3>

<p>
<b>Description:</b>
<?php echo nl2br(htmlspecialchars($row['task_description'])); ?>
</p>

<p>
<b>Deadline:</b>
<?php echo htmlspecialchars($row['deadline']); ?>
</p>

<p>

<b>Status:</b>

<?php

if ($row['status'] == "Pending") {

echo "<span class='pending'>🟡 Pending</span>";

} elseif ($row['status'] == "In Progress") {

echo "<span class='progress'>🔵 In Progress</span>";

} else {

echo "<span class='completed'>🟢 Completed</span>";

}

?>

</p>

<a
class="update-btn"
href="update_task.php?id=<?php echo $row['id']; ?>">
✏ Update Status </a>

</div>

<?php

}

} else {

?>

<div class="no-task">
📭 No Tasks Assigned Yet
</div>

<?php

}

$stmt->close();

?>

</div>

</body>
</html>