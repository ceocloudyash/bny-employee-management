<?php

include 'db.php';

$id=$_GET['id'];

$conn->query(
"DELETE FROM holidays
WHERE id='$id'"
);

header("Location:holiday_list.php");
exit();

?>