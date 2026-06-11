<?php

session_start();

if(!isset($_SESSION['role']) || $_SESSION['role']!='CEO')
{
    header("Location:index.php");
    exit();
}

include 'db.php';

if(!isset($_GET['id']))
{
    die("Employee ID Missing");
}

$employee_id = $_GET['id'];

$employee = $conn->query(
"SELECT * FROM employees
WHERE employee_id='$employee_id'"
);

if($employee->num_rows == 0)
{
    die("Employee Not Found");
}

$emp = $employee->fetch_assoc();

$message = "";

if(isset($_POST['save']))
{
    $review_date = $_POST['review_date'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $reviewer = $_SESSION['username'];

    if(
    $conn->query(
    "INSERT INTO employee_performance
    (
    employee_id,
    review_date,
    rating,
    feedback,
    reviewer
    )
    VALUES
    (
    '$employee_id',
    '$review_date',
    '$rating',
    '$feedback',
    '$reviewer'
    )"))
    {
        $message = "✅ Performance Review Added Successfully";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Performance Review</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:20px;
margin:0;
}

.dashboard-btn{
display:inline-block;
padding:10px 15px;
background:#22c55e;
color:white;
text-decoration:none;
border-radius:8px;
margin-bottom:15px;
}

.dashboard-btn:hover{
background:#16a34a;
}

.card{
background:#1e293b;
padding:25px;
border-radius:15px;
max-width:800px;
margin:auto;
}

h2{
color:#22d3ee;
margin-top:0;
}

.info{
background:#0f172a;
padding:12px;
border-radius:8px;
margin-bottom:10px;
}

input,
select,
textarea{
width:100%;
padding:12px;
margin-top:10px;
margin-bottom:15px;
border:none;
border-radius:8px;
background:#0f172a;
color:white;
box-sizing:border-box;
}

button{
padding:12px 20px;
background:#06b6d4;
border:none;
color:white;
border-radius:8px;
cursor:pointer;
font-size:15px;
}

button:hover{
background:#0891b2;
}

.success{
background:#14532d;
padding:12px;
border-radius:8px;
margin-bottom:15px;
}

.back-btn{
display:inline-block;
padding:10px 15px;
background:#06b6d4;
color:white;
text-decoration:none;
border-radius:8px;
margin-top:15px;
}

</style>

</head>

<body>

<a class="dashboard-btn" href="dashboard.php">
🏠 Dashboard
</a>

<div class="card">

<h2>⭐ Add Performance Review</h2>

<div class="info">
<b>Employee ID:</b> <?php echo $emp['employee_id']; ?>
</div>

<div class="info">
<b>Name:</b> <?php echo $emp['name']; ?>
</div>

<div class="info">
<b>Department:</b> <?php echo $emp['department']; ?>
</div>

<?php if(!empty($message)) { ?>

<div class="success">
<?php echo $message; ?>
</div>

<?php } ?>

<form method="POST">

<input
type="date"
name="review_date"
required>

<select
name="rating"
required>

<option value="">Select Rating</option>
<option value="1">⭐ 1 Star</option>
<option value="2">⭐⭐ 2 Stars</option>
<option value="3">⭐⭐⭐ 3 Stars</option>
<option value="4">⭐⭐⭐⭐ 4 Stars</option>
<option value="5">⭐⭐⭐⭐⭐ 5 Stars</option>

</select>

<textarea
name="feedback"
rows="6"
placeholder="Performance Feedback"
required></textarea>

<button
type="submit"
name="save">

💾 Save Review

</button>

</form>

<a class="back-btn" href="employees.php">
⬅ Back To Employees
</a>

</div>

</body>

</html>