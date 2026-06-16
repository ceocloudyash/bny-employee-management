<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

if(!isset($_GET['id']))
{
    die("Task ID Missing");
}

$id = $_GET['id'];

$result = $conn->query(
"SELECT *
FROM tasks
WHERE id='$id'"
);

if($result->num_rows==0)
{
    die("Task Not Found");
}

$task = $result->fetch_assoc();

if(isset($_POST['save']))
{
    $status = $_POST['status'];

    $conn->query(
    "UPDATE tasks
    SET status='$status'
    WHERE id='$id'"
    );

    header("Location:my_tasks.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Update Task</title>

<style>

body{
background:#020617;
font-family:'Segoe UI';
color:white;
margin:0;
padding:30px;
}

.container{
max-width:700px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
box-shadow:0 0 20px rgba(0,0,0,.3);
}

h1{
color:#22d3ee;
text-align:center;
margin-bottom:25px;
}

.info{
background:#0f172a;
padding:15px;
border-radius:10px;
margin-bottom:15px;
}

.label{
color:#22d3ee;
font-weight:bold;
}

select{
width:100%;
padding:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
margin-top:10px;
margin-bottom:20px;
font-size:15px;
}

button{
width:100%;
padding:15px;
background:#06b6d4;
border:none;
border-radius:10px;
color:white;
font-size:16px;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#0891b2;
}

.btn{
display:inline-block;
padding:12px 18px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:10px;
font-weight:bold;
margin-bottom:20px;
}

.btn:hover{
background:#16a34a;
}

</style>

</head>

<body>

<a
class="btn"
href="my_tasks.php">

⬅ Back To My Tasks

</a>

<div class="container">

<h1>📋 Update Task Status</h1>

<div class="info">
<span class="label">Task:</span>
<?php echo $task['task_title']; ?>
</div>

<div class="info">
<span class="label">Description:</span>
<?php echo $task['task_description']; ?>
</div>

<div class="info">
<span class="label">Deadline:</span>
<?php echo $task['deadline']; ?>
</div>

<form method="POST">

<label class="label">

Update Status

</label>

<select name="status" required>

<option
value="Pending"
<?php if($task['status']=="Pending") echo "selected"; ?>>
🟡 Pending
</option>

<option
value="In Progress"
<?php if($task['status']=="In Progress") echo "selected"; ?>>
🔵 In Progress
</option>

<option
value="Completed"
<?php if($task['status']=="Completed") echo "selected"; ?>>
🟢 Completed
</option>

</select>

<button
type="submit"
name="save">

💾 Save Status

</button>

</form>

</div>

</body>

</html>