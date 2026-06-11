<?php

session_start();

if(!isset($_SESSION['employee_id']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION['employee_id'];

$result = $conn->query(
"SELECT * FROM employees
WHERE employee_id='$employee_id'"
);

if($result->num_rows == 0)
{
    die("Employee not found");
}

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile</title>

<style>

body{
background:#020617;
font-family:'Segoe UI',sans-serif;
color:white;
padding:30px;
margin:0;
}

.top-bar{
display:flex;
justify-content:flex-end;
margin-bottom:20px;
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

.card{
max-width:800px;
margin:auto;
background:#1e293b;
padding:35px;
border-radius:20px;
box-shadow:0 0 20px rgba(0,0,0,.3);
}

.profile-image{
width:150px;
height:150px;
border-radius:50%;
object-fit:cover;
display:block;
margin:auto;
border:4px solid #22d3ee;
}

h1{
text-align:center;
color:#22d3ee;
margin-top:20px;
margin-bottom:30px;
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

.btn{
display:inline-block;
padding:12px 18px;
background:#22d3ee;
color:black;
text-decoration:none;
border-radius:10px;
font-weight:bold;
margin-top:15px;
margin-right:10px;
}

.btn:hover{
opacity:.9;
}

.documents{
margin-top:25px;
}

.documents h2{
color:#22d3ee;
}

.documents a{
display:block;
color:#22d3ee;
margin-bottom:10px;
text-decoration:none;
padding:10px;
background:#0f172a;
border-radius:8px;
}

.documents a:hover{
background:#172554;
}

.no-docs{
background:#0f172a;
padding:12px;
border-radius:8px;
color:#94a3b8;
}

</style>

</head>

<body>

<div class="top-bar">

<a
class="dashboard-btn"
href="employee_dashboard.php">

🏠 Back to Dashboard

</a>

</div>

<div class="card">

<?php

$photo = "";

if(!empty($row['profile_photo']))
{
    $photo = $row['profile_photo'];
}
elseif(!empty($row['photo']))
{
    $photo = $row['photo'];
}

if($photo != "")
{

?>

<img
class="profile-image"
src="uploads/<?php echo $photo; ?>">

<?php

}

?>

<h1>👤 My Profile</h1>

<div class="info">
<span class="label">Employee ID:</span>
<?php echo $row['employee_id']; ?>
</div>

<div class="info">
<span class="label">Name:</span>
<?php echo $row['name']; ?>
</div>

<div class="info">
<span class="label">Email:</span>
<?php echo $row['email']; ?>
</div>

<div class="info">
<span class="label">Department:</span>
<?php echo $row['department']; ?>
</div>

<div class="info">
<span class="label">Position:</span>
<?php echo $row['position']; ?>
</div>

<div class="info">
<span class="label">Salary:</span>
₹<?php echo $row['salary']; ?>
</div>

<div class="documents">

<h2>📁 My Documents</h2>

<?php

$docs = $conn->query(
"SELECT * FROM employee_documents
WHERE employee_id='$employee_id'
ORDER BY id DESC"
);

if($docs->num_rows > 0)
{
    while($doc = $docs->fetch_assoc())
    {
?>

<a
href="uploads/documents/<?php echo $doc['file_name']; ?>"
target="_blank">

📄 <?php echo $doc['document_type']; ?>
-
<?php echo $doc['document_name']; ?>

</a>

<?php

    }
}
else
{
?>

<div class="no-docs">
No documents uploaded yet.
</div>

<?php
}

?>

</div>

<br>

<a
class="btn"
href="upload_documents.php">

📤 Upload Documents

</a>

<a
class="btn"
href="employee_dashboard.php">

🏠 Dashboard

</a>

</div>

</body>

</html>