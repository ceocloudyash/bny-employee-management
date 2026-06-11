<?php
ob_start();
session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    /* CEO LOGIN */

    $stmt = $conn->prepare(
        "SELECT * FROM users
         WHERE username = ?
         AND password = ?"
    );

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        session_regenerate_id(true);

        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = 'CEO';

        header("Location: dashboard.php");
        exit();
    }

    /* EMPLOYEE LOGIN */

    $stmt2 = $conn->prepare(
        "SELECT * FROM employees
         WHERE login_username = ?
         AND login_password = ?"
    );

    $stmt2->bind_param("ss", $username, $password);
    $stmt2->execute();

    $result2 = $stmt2->get_result();

    if ($result2->num_rows > 0)
    {
        $row = $result2->fetch_assoc();

        session_regenerate_id(true);

        $_SESSION['employee_id'] = $row['employee_id'];
        $_SESSION['employee_name'] = $row['name'];
        $_SESSION['role'] = 'EMPLOYEE';

        header("Location: employee_dashboard.php");
        exit();
    }

    echo "
    <html>
    <head>
    <title>Login Failed</title>

    <style>
    body{
        background:#020617;
        color:white;
        font-family:Segoe UI;
        text-align:center;
        padding-top:100px;
    }

    .box{
        width:400px;
        margin:auto;
        background:#1e293b;
        padding:30px;
        border-radius:15px;
    }

    a{
        color:#22d3ee;
        text-decoration:none;
    }
    </style>

    </head>

    <body>

    <div class='box'>

    <h2>❌ Invalid Username or Password</h2>

    <br>

    <a href='index.php'>
    Back To Login
    </a>

    </div>

    </body>

    </html>
    ";
}

$conn->close();
ob_end_flush();
?>
