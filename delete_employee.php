<?php

session_start();

include 'db.php';

$id = $_GET['id'];

$conn->query(
"DELETE FROM employees WHERE id='$id'"
);

header("Location: employees.php");

exit();

?>