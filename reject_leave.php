<?php

include 'db.php';

$id=$_GET['id'];

$conn->query(
"UPDATE leave_requests
SET status='Rejected'
WHERE id='$id'"
);

header("Location:leave_requests.php");
$leave = $conn->query(
"SELECT * FROM leave_requests WHERE id='$id'"
);

$row = $leave->fetch_assoc();

$employee_id = $row['employee_id'];

$conn->query(
"INSERT INTO notifications
(
employee_id,
title,
message
)
VALUES
(
'$employee_id',
'Leave Rejected',
'Your leave request has been rejected.'
)"
);

?>