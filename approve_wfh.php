<?php

include 'db.php';

$id=$_GET['id'];

$conn->query(
"UPDATE wfh_requests
SET status='Approved'
WHERE id='$id'"
);

header("Location:wfh_requests.php");
exit();

?>