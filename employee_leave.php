<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['employee_id'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $employee_id   = $_SESSION['employee_id'];
    $employee_name = $_SESSION['employee_name'];

    $leave_from = trim($_POST['leave_from']);
    $leave_to   = trim($_POST['leave_to']);
    $reason     = trim($_POST['reason']);

    $stmt = $conn->prepare(
        "INSERT INTO leave_requests
        (employee_id, employee_name, leave_from, leave_to, reason)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssss",
        $employee_id,
        $employee_name,
        $leave_from,
        $leave_to,
        $reason
    );

    if ($stmt->execute()) {
        $message = "✅ Leave Applied Successfully";
    } else {
        $message = "❌ Failed To Apply Leave";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Apply Leave</title>

<style>

body{
background:#020617;
color:white;
font-family:'Segoe UI',sans-serif;
margin:0;
padding:30px;
}

.container{
max-width:800px;
margin:auto;
background:#1e293b;
padding:30px;
border-radius:20px;
box-shadow:0 0 25px rgba(0,0,0,.3);
}

.top-bar{
display:flex;
justify-content:space-between;
margin-bottom:20px;
}

.btn{
padding:10px 15px;
text-decoration:none;
border-radius:8px;
color:white;
font-weight:bold;
}

.back{
background:#06b6d4;
}

.back:hover{
background:#0891b2;
}

.dashboard{
background:#22c55e;
}

.dashboard:hover{
background:#16a34a;
}

h1{
color:#22d3ee;
margin-bottom:20px;
}

.info{
background:#0f172a;
padding:12px;
border-radius:10px;
margin-bottom:10px;
}

.success{
background:#14532d;
padding:12px;
border-radius:10px;
margin-bottom:15px;
}

.error{
background:#7f1d1d;
padding:12px;
border-radius:10px;
margin-bottom:15px;
}

label{
display:block;
margin-bottom:8px;
margin-top:15px;
}

input,
textarea{
width:100%;
padding:12px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
box-sizing:border-box;
}

button{
width:100%;
padding:15px;
margin-top:20px;
background:#22d3ee;
border:none;
border-radius:10px;
font-size:16px;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#06b6d4;
}

</style>

</head>

<body>

<div class="container">

<div class="top-bar">

<a href="employee_dashboard.php" class="btn back">
⬅ Back
</a>

<a href="employee_dashboard.php" class="btn dashboard">
🏠 Dashboard
</a>

</div>

<h1>📝 Apply Leave</h1>

<div class="info">
<b>Employee ID:</b>
<?php echo htmlspecialchars($_SESSION['employee_id']); ?>
</div>

<div class="info">
<b>Employee Name:</b>
<?php echo htmlspecialchars($_SESSION['employee_name']); ?>
</div>

<?php if ($message != "") { ?>
<div class="success">
<?php echo $message; ?>
</div>
<?php } ?>

<form method="POST">

<label>Leave From</label>
<input
type="date"
name="leave_from"
required>

<label>Leave To</label>
<input
type="date"
name="leave_to"
required>

<label>Reason</label>
<textarea
name="reason"
rows="5"
required></textarea>

<button type="submit">
📨 Apply Leave
</button>

</form>

</div>

</body>

</html>

<?php
$conn->close();
ob_end_flush();
?>