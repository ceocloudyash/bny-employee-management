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
FROM employees
ORDER BY name ASC"
);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Employee Chats</title>

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

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

h1{
color:#22d3ee;
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

.container{
background:#1e293b;
padding:20px;
border-radius:15px;
}

.search{
width:100%;
padding:12px;
margin-bottom:20px;
background:#0f172a;
border:none;
border-radius:10px;
color:white;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#0f172a;
padding:15px;
color:#22d3ee;
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid #334155;
}

tr:hover{
background:#273449;
}

.chat-btn{
background:#06b6d4;
color:white;
padding:10px 15px;
text-decoration:none;
border-radius:8px;
font-size:14px;
font-weight:bold;
}

.chat-btn:hover{
background:#0891b2;
}

.photo{
width:50px;
height:50px;
border-radius:50%;
object-fit:cover;
border:2px solid #22d3ee;
}

.no-photo{
width:50px;
height:50px;
border-radius:50%;
background:#334155;
display:flex;
align-items:center;
justify-content:center;
font-size:12px;
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
tr[i].getElementsByTagName("td")[2];

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

<div class="topbar">

<h1>💬 Employee Chats</h1>

<a
href="dashboard.php"
class="dashboard-btn">

🏠 Dashboard

</a>

</div>

<div class="container">

<input
type="text"
id="search"
class="search"
onkeyup="searchEmployee()"
placeholder="🔍 Search Employee">

<table id="employeeTable">

<tr>

<th>Photo</th>
<th>Employee ID</th>
<th>Name</th>
<th>Department</th>
<th>Chat</th>

</tr>

<?php

while($row=$result->fetch_assoc())
{

?>

<tr>

<td>

<?php

if(!empty($row['photo']))
{

?>

<img
class="photo"
src="uploads/<?php echo $row['photo']; ?>">

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

<td>
<?php echo $row['employee_id']; ?>
</td>

<td>
<?php echo $row['name']; ?>
</td>

<td>
<?php echo $row['department']; ?>
</td>

<td>

<a
class="chat-btn"
href="chat.php?id=<?php echo $row['employee_id']; ?>">

💬 Open Chat

</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>
