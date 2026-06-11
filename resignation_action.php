<?php

session_start();

if($_SESSION['role']!='CEO')
{
header("Location:index.php");
exit();
}

include 'db.php';

$id=$_GET['id'];
$status=$_GET['status'];

$conn->query(
"UPDATE resignations
SET status='$status'
WHERE id='$id'"
);

if($status=="Approved")
{
$r=$conn->query(
"SELECT *
FROM resignations
WHERE id='$id'"
);

$data=$r->fetch_assoc();

$employee_id=$data['employee_id'];

$conn->query(
"DELETE FROM employees
WHERE employee_id='$employee_id'"
);
}

header("Location:resignation_requests.php");

?>
