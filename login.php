
<?php

session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    /* CEO LOGIN */

    $sql = "SELECT * FROM users
            WHERE username='$username'
            AND password='$password'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        /* CLEAR OLD SESSION */

        session_unset();
        session_regenerate_id(true);

        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = 'CEO';

        header("Location: dashboard.php");
        exit();
    }

    /* EMPLOYEE LOGIN */

    $sql2 = "SELECT * FROM employees
             WHERE login_username='$username'
             AND login_password='$password'";

    $result2 = $conn->query($sql2);

    if ($result2 && $result2->num_rows > 0)
    {
        $row = $result2->fetch_assoc();

        /* CLEAR OLD SESSION */

        session_unset();
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
        font-family:'Segoe UI';
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

?>

