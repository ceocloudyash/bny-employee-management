<?php

session_start();

if(!isset($_SESSION['role']) || $_SESSION['role']!='CEO')
{
    header("Location:index.php");
    exit();
}

include 'db.php';

$message="";

if(isset($_POST['post']))
{
    $title=$_POST['title'];
    $announcement=$_POST['message'];

    $conn->query(
    "INSERT INTO announcements
    (
    title,
    message
    )
    VALUES
    (
    '$title',
    '$announcement'
    )"
    );

    $message="Announcement Posted Successfully";
}

$result=$conn->query(
"SELECT *
FROM announcements
ORDER BY id DESC"
);

?>

<!DOCTYPE html>

<html>
<head>
<title>Company Announcements</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:30px;
}

.container{
max-width:900px;
margin:auto;
}

.box{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
}

input,textarea{
width:100%;
padding:15px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:#0f172a;
color:white;
}

button{
width:100%;
padding:15px;
background:#22d3ee;
border:none;
border-radius:10px;
font-weight:bold;
cursor:pointer;
}

.btn{
background:#22c55e;
padding:10px 15px;
border-radius:8px;
color:white;
text-decoration:none;
}

.success{
color:#22c55e;
font-weight:bold;
}

.notice{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:15px;
}

.notice h3{
color:#22d3ee;
}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="btn">
🏠 Dashboard
</a>

<br><br>

<div class="box">

<h2>📢 Post Announcement</h2>

<?php echo "<p class='success'>$message</p>"; ?>

<form method="POST">

<input
type="text"
name="title"
placeholder="Announcement Title"
required>

<textarea
name="message"
placeholder="Announcement Message"
required></textarea>

<button
type="submit"
name="post">

Post Announcement

</button>

</form>

</div>

<h2>Recent Announcements</h2>

<?php

while($row=$result->fetch_assoc())
{

?>

<div class="notice">

<h3>
<?php echo $row['title']; ?>
</h3>

<p>
<?php echo $row['message']; ?>
</p>

<small>
<?php echo $row['created_at']; ?>
</small>

</div>

<?php

}

?>

</div>

</body>
</html>
