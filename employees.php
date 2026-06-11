<?php

session_start();

if(!isset($_SESSION['username']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$result = $conn->query(
"SELECT * FROM employees ORDER BY id DESC"
);

$totalEmployees = $conn->query(
"SELECT COUNT(*) AS total FROM employees"
);

$total = $totalEmployees->fetch_assoc()['total'];

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Employees Management</title>

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
padding:20px;
}

.top-bar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
flex-wrap:wrap;
gap:10px;
}

h1{
color:#22d3ee;
}

.dashboard-btn{
padding:12px 18px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:10px;
font-weight:bold;
}

.dashboard-btn:hover{
background:#16a34a;
}

.top-btn{
display:inline-block;
padding:12px 18px;
background:#06b6d4;
color:white;
text-decoration:none;
border-radius:10px;
margin-bottom:20px;
font-weight:bold;
}

.top-btn:hover{
background:#0891b2;
}

.stats{
background:#1e293b;
padding:18px;
border-radius:15px;
margin-bottom:20px;
font-size:18px;
}

.search-box{
width:100%;
padding:14px;
margin-bottom:20px;
border:none;
border-radius:10px;
background:#1e293b;
color:white;
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

img{
width:60px;
height:60px;
border-radius:50%;
object-fit:cover;
border:2px solid #22d3ee;
}

.no-photo{
width:60px;
height:60px;
border-radius:50%;
background:#334155;
display:flex;
align-items:center;
justify-content:center;
font-size:12px;
}

.btn{
display:inline-block;
padding:8px 12px;
background:#06b6d4;
color:white;
text-decoration:none;
border-radius:8px;
margin:2px;
font-size:12px;
}

.btn:hover{
background:#0891b2;
}

.actions{
min-width:450px;
}

</style>

<script>

function searchEmployee()
{
let input =
document.getElementById("search");

let filter =
input.value.toUpperCase();

let table =
document.getElementById("employeeTable");

let tr =
table.getElementsByTagName("tr");

for(let i=1;i<tr.length;i++)
{
let td =
tr[i].getElementsByTagName("td")[3];

if(td)
{
let txt =
td.textContent ||
td.innerText;

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

<div class="top-bar">

<h1>👥 Employees Management</h1>

<a href="dashboard.php" class="dashboard-btn">
🏠 Dashboard
</a>

</div>

<div class="stats">
👨‍💼 Total Employees :
<b><?php echo $total; ?></b>
</div>

<a href="add_employee.php" class="top-btn">
➕ Add Employee
</a>

<input
type="text"
id="search"
class="search-box"
onkeyup="searchEmployee()"
placeholder="🔍 Search Employee By Name">

<table id="employeeTable">

<tr>

<th>Photo</th>
<th>ID</th>
<th>Employee ID</th>
<th>Name</th>
<th>Email</th>
<th>Department</th>
<th>Position</th>
<th>Salary</th>
<th>Username</th>
<th>Actions</th>

</tr>

<?php

while($row = $result->fetch_assoc())
{

?>

<tr>

<td>

<?php

if(!empty($row['profile_photo']))
{
?>

<img src="uploads/<?php echo $row['profile_photo']; ?>">

<?php
}
elseif(!empty($row['photo']))
{
?>

<img src="uploads/<?php echo $row['photo']; ?>">

<?php
}
else
{
?>

<div class="no-photo">
N/A
</div>

<?php
}

?>

</td>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['employee_id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['department']; ?></td>
<td><?php echo $row['position']; ?></td>
<td>₹<?php echo $row['salary']; ?></td>
<td><?php echo $row['login_username']; ?></td>

<td class="actions">

<a class="btn"
href="view_employee.php?id=<?php echo $row['employee_id']; ?>">
👁 View </a>

<a class="btn"
href="edit_employee.php?id=<?php echo $row['employee_id']; ?>">
✏ Edit </a>

<a class="btn"
href="upload_documents.php?id=<?php echo $row['employee_id']; ?>">
📄 Documents </a>

<a class="btn"
href="add_performance.php?id=<?php echo $row['employee_id']; ?>">
⭐ Review </a>

<a class="btn"
href="performance_history.php?id=<?php echo $row['employee_id']; ?>">
📊 History </a>

<a class="btn"
href="chat.php?id=<?php echo $row['employee_id']; ?>">
💬 Chat </a>

</td>

</tr>

<?php

}

?>

</table>

</body>
</html>
