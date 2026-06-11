<?php

session_start();

if(
!isset($_SESSION['employee_id'])
)
{
header("Location:index.php");
exit();
}

include 'db.php';

if(isset($_POST['submit']))
{

$employee_id=$_SESSION['employee_id'];

$employee_name=$_SESSION['employee_name'];

$from_date=$_POST['from_date'];

$to_date=$_POST['to_date'];

$reason=$_POST['reason'];

$conn->query(
"INSERT INTO wfh_requests
(
employee_id,
employee_name,
from_date,
to_date,
reason
)
VALUES
(
'$employee_id',
'$employee_name',
'$from_date',
'$to_date',
'$reason'
)"
);

echo "<script>
alert('WFH Request Submitted');
window.location='my_wfh.php';
</script>";

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Apply WFH</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:30px;
}

.container{
max-width:600px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

input,textarea{
width:100%;
padding:12px;
margin:10px 0;
background:#0f172a;
color:white;
border:none;
border-radius:10px;
}

button{
width:100%;
padding:15px;
background:#22d3ee;
border:none;
border-radius:10px;
font-weight:bold;
}

</style>

</head>

<body>

<div class="container">

<h2>🏠 Work From Home Request</h2>

<form method="POST">

<label>From Date</label>

<input
type="date"
name="from_date"
required>

<label>To Date</label>

<input
type="date"
name="to_date"
required>

<label>Reason</label>

<textarea
name="reason"
required></textarea>

<button
name="submit">

Submit Request

</button>

</form>

</div>

</body>
</html>