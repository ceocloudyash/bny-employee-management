<?php

session_start();
include 'db.php';

/* LOGIN CHECK */

if(
    !isset($_SESSION['employee_id']) &&
    !isset($_SESSION['username'])
)
{
    header("Location:index.php");
    exit();
}

/* WHO IS LOGGED IN */

if(isset($_SESSION['employee_id']))
{
    $my_id = $_SESSION['employee_id'];
    $my_name = $_SESSION['employee_name'];
    $dashboard = "employee_dashboard.php";
}
else
{
    $my_id = "CEO";
    $my_name = $_SESSION['username'];
    $dashboard = "dashboard.php";
}

/* RECEIVER */

if(!isset($_GET['id']))
{
    die("Receiver ID Missing");
}

$receiver = trim($_GET['id']);

/* SEND MESSAGE */

if(isset($_POST['send']))
{
    $message = trim($_POST['message']);

    if($message != "")
    {
        $sql = "
        INSERT INTO messages
        (
            sender_id,
            sender_name,
            receiver_id,
            message
        )
        VALUES
        (
            '$my_id',
            '$my_name',
            '$receiver',
            '$message'
        )";

        if(!$conn->query($sql))
        {
            die("INSERT ERROR : " . $conn->error);
        }

        header("Location: chat.php?id=".$receiver);
        exit();
    }
}

/* LOAD CHAT */

$sql = "
SELECT *
FROM messages
WHERE
(
sender_id='$my_id'
AND
receiver_id='$receiver'
)
OR
(
sender_id='$receiver'
AND
receiver_id='$my_id'
)
ORDER BY id ASC
";

$result = $conn->query($sql);

if(!$result)
{
    die("QUERY ERROR : ".$conn->error);
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Internal Chat</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:20px;
}

.chat-container{
max-width:1000px;
margin:auto;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.title{
font-size:32px;
font-weight:bold;
color:#22d3ee;
}

.dashboard-btn{
background:#22c55e;
color:white;
padding:12px 18px;
text-decoration:none;
border-radius:10px;
}

.debug{
background:#facc15;
color:black;
padding:20px;
border-radius:15px;
margin-bottom:20px;
font-weight:bold;
line-height:1.8;
}

.chat-header{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
}

.chat-header h2{
color:#22d3ee;
}

.chat-box{
height:500px;
overflow-y:auto;
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
}

.me{
background:#22d3ee;
color:black;
padding:12px;
border-radius:12px;
margin:10px 0;
margin-left:auto;
max-width:70%;
}

.other{
background:#334155;
padding:12px;
border-radius:12px;
margin:10px 0;
max-width:70%;
}

.sender{
font-weight:bold;
font-size:12px;
margin-bottom:5px;
}

.time{
font-size:11px;
margin-top:5px;
opacity:.7;
}

textarea{
width:100%;
height:100px;
padding:15px;
background:#0f172a;
color:white;
border:none;
border-radius:12px;
resize:none;
}

button{
width:100%;
padding:15px;
margin-top:10px;
background:#06b6d4;
color:white;
border:none;
border-radius:12px;
font-size:16px;
cursor:pointer;
}

.empty{
text-align:center;
padding-top:40px;
color:#94a3b8;
}

</style>

</head>

<body>

<div class="chat-container">

<div class="topbar">

<div class="title">
💬 Internal Chat
</div>

<a
href="<?php echo $dashboard; ?>"
class="dashboard-btn">
🏠 Dashboard
</a>

</div>

<div class="debug">

SESSION ROLE :
<?php echo isset($_SESSION['role']) ? $_SESSION['role'] : 'NOT SET'; ?>

<br>

MY ID :
<?php echo $my_id; ?>

<br>

MY NAME :
<?php echo $my_name; ?>

<br>

RECEIVER :
<?php echo $receiver; ?>

<br>

MESSAGES FOUND :
<?php echo $result->num_rows; ?>

<br>

CURRENT URL :
<?php echo $_SERVER['REQUEST_URI']; ?>

</div>

<div class="chat-header">

<h2>
Conversation With :
<?php echo htmlspecialchars($receiver); ?>
</h2>

</div>

<div class="chat-box" id="chatbox">

<?php

if($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        if($row['sender_id'] == $my_id)
        {
            echo "
            <div class='me'>
                <div class='sender'>You</div>
                ".htmlspecialchars($row['message'])."
                <div class='time'>".$row['created_at']."</div>
            </div>";
        }
        else
        {
            echo "
            <div class='other'>
                <div class='sender'>".htmlspecialchars($row['sender_name'])."</div>
                ".htmlspecialchars($row['message'])."
                <div class='time'>".$row['created_at']."</div>
            </div>";
        }
    }
}
else
{
    echo "<div class='empty'>No messages found.</div>";
}

?>

</div>

<form method="POST">

<textarea
name="message"
placeholder="Type your message..."
required></textarea>

<button
type="submit"
name="send">

📨 Send Message

</button>

</form>

</div>

<script>

var box =
document.getElementById("chatbox");

box.scrollTop =
box.scrollHeight;

</script>

</body>

</html>