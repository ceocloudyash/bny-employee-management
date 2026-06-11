<?php

session_start();

include 'db.php';

if(
    !isset($_SESSION['username']) &&
    !isset($_SESSION['employee_id'])
)
{
    header("Location:index.php");
    exit();
}

$message = "";

if(isset($_POST['change']))
{
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($new_password != $confirm_password)
    {
        $message = "New passwords do not match!";
    }
    else
    {
        /* CEO PASSWORD CHANGE */

        if(isset($_SESSION['username']))
        {
            $username = $_SESSION['username'];

            $result = $conn->query(
            "SELECT * FROM users
            WHERE username='$username'"
            );

            $user = $result->fetch_assoc();

            if($user['password'] != $current_password)
            {
                $message = "Current password is incorrect!";
            }
            else
            {
                $conn->query(
                "UPDATE users
                SET password='$new_password'
                WHERE username='$username'"
                );

                $message = "Password changed successfully!";
            }
        }

        /* EMPLOYEE PASSWORD CHANGE */

        if(isset($_SESSION['employee_id']))
        {
            $employee_id = $_SESSION['employee_id'];

            $result = $conn->query(
            "SELECT * FROM employees
            WHERE employee_id='$employee_id'"
            );

            $employee = $result->fetch_assoc();

            if($employee['login_password'] != $current_password)
            {
                $message = "Current password is incorrect!";
            }
            else
            {
                $conn->query(
                "UPDATE employees
                SET login_password='$new_password'
                WHERE employee_id='$employee_id'"
                );

                $message = "Password changed successfully!";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Change Password</title>

<style>

body{
background:#020617;
font-family:Segoe UI;
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
padding:10px 15px;
text-decoration:none;
border-radius:8px;
font-weight:bold;
}

.card{
max-width:500px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
}

h2{
text-align:center;
color:#22d3ee;
}

input{
width:100%;
padding:15px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
box-sizing:border-box;
}

button{
width:100%;
padding:15px;
background:#06b6d4;
border:none;
border-radius:10px;
color:white;
font-size:16px;
cursor:pointer;
}

.message{
text-align:center;
margin-bottom:15px;
color:#22c55e;
font-weight:bold;
}

</style>

</head>

<body>

<div class="top-bar">

<?php if(isset($_SESSION['username'])) { ?>

<a
class="dashboard-btn"
href="dashboard.php">

🏠 Dashboard

</a>

<?php } else { ?>

<a
class="dashboard-btn"
href="employee_dashboard.php">

🏠 Dashboard

</a>

<?php } ?>

</div>

<div class="card">

<h2>🔑 Change Password</h2>

<?php

if($message != "")
{
    echo "<div class='message'>$message</div>";
}

?>

<form method="POST">

<input
type="password"
name="current_password"
placeholder="Current Password"
required>

<input
type="password"
name="new_password"
placeholder="New Password"
required>

<input
type="password"
name="confirm_password"
placeholder="Confirm New Password"
required>

<button
type="submit"
name="change">

Update Password

</button>

</form>

</div>

</body>

</html>