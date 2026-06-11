<?php

$host = $_ENV['DB_HOST'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? '';
$port = (int)($_ENV['DB_PORT'] ?? 3306);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try
{
    $conn = new mysqli(
        $host,
        $user,
        $pass,
        $db,
        $port
    );

    $conn->set_charset("utf8mb4");
}
catch (Exception $e)
{
    die(
        "<h3 style='font-family:Segoe UI;color:red'>
        Database Error:
        " . htmlspecialchars($e->getMessage()) . "
        </h3>"
    );
}
?>