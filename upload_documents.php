
<?php

session_start();

if(!isset($_SESSION['role']))
{
    header("Location:index.php");
    exit();
}

include 'db.php';

/* CEO */

if(isset($_GET['id']))
{
    $employee_id = $_GET['id'];
}

/* Employee */

else if($_SESSION['role'] == 'EMPLOYEE')
{
    $employee_id = $_SESSION['employee_id'];
}

else
{
    die("Employee ID not found");
}

/* Upload Document */

if(isset($_POST['upload']))
{
    $document_name = $_POST['document_name'];
    $document_type = $_POST['document_type'];

    $file_name =
    time().'_'.
    basename($_FILES['document']['name']);

    $target =
    "uploads/documents/" .
    $file_name;

    if(move_uploaded_file(
        $_FILES['document']['tmp_name'],
        $target))
    {
        $conn->query(
        "INSERT INTO employee_documents
        (
        employee_id,
        document_name,
        document_type,
        file_name
        )
        VALUES
        (
        '$employee_id',
        '$document_name',
        '$document_type',
        '$file_name'
        )");
    }
}

/* Delete Document */

if(isset($_GET['delete']))
{
    if($_SESSION['role'] == 'CEO')
    {
        $delete_id = $_GET['delete'];

        $result =
        $conn->query(
        "SELECT * FROM employee_documents
        WHERE id='$delete_id'"
        );

        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();

            $filepath =
            "uploads/documents/" .
            $row['file_name'];

            if(file_exists($filepath))
            {
                unlink($filepath);
            }

            $conn->query(
            "DELETE FROM employee_documents
            WHERE id='$delete_id'"
            );
        }

        header(
        "Location: upload_documents.php?id=$employee_id"
        );
        exit();
    }
}

$documents =
$conn->query(
"SELECT * FROM employee_documents
WHERE employee_id='$employee_id'
ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Employee Documents</title>

<style>

body{
background:#020617;
color:white;
font-family:Segoe UI;
padding:20px;
margin:0;
}

h1{
color:#22d3ee;
}

.card{
background:#1e293b;
padding:20px;
border-radius:12px;
margin-bottom:20px;
}

input,
select{
width:100%;
padding:12px;
margin:10px 0;
border:none;
border-radius:8px;
}

button{
padding:12px 20px;
background:#06b6d4;
color:white;
border:none;
border-radius:8px;
cursor:pointer;
}

button:hover{
background:#0891b2;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
}

th{
background:#0f172a;
color:#22d3ee;
padding:15px;
}

td{
padding:15px;
border-bottom:1px solid #334155;
}

a{
color:#22d3ee;
text-decoration:none;
}

.back{
display:inline-block;
margin-top:20px;
padding:10px 15px;
background:#06b6d4;
color:white;
border-radius:8px;
text-decoration:none;
}

.delete-btn{
color:#ef4444;
}

</style>

</head>

<body>

<h1>📄 Employee Documents</h1>

<div class="card">

<form method="POST" enctype="multipart/form-data">

<select
name="document_type"
required>

<option value="">
Select Document Type
</option>

<option value="Resume">
Resume
</option>

<option value="PAN Card">
PAN Card
</option>

<option value="Aadhaar Card">
Aadhaar Card
</option>

<option value="Certificate">
Certificate
</option>

<option value="Offer Letter">
Offer Letter
</option>

<option value="Other">
Other
</option>

</select>

<input
type="text"
name="document_name"
placeholder="Document Name"
required>

<input
type="file"
name="document"
required>

<button
type="submit"
name="upload">

Upload Document

</button>

</form>

</div>

<table>

<tr>

<th>ID</th>
<th>Type</th>
<th>Name</th>
<th>Download</th>

<?php if($_SESSION['role']=='CEO') { ?>
<th>Delete</th>
<?php } ?>

</tr>

<?php

while($doc = $documents->fetch_assoc())
{

?>

<tr>

<td><?php echo $doc['id']; ?></td>

<td><?php echo $doc['document_type']; ?></td>

<td><?php echo $doc['document_name']; ?></td>

<td>

<a
href="uploads/documents/<?php echo $doc['file_name']; ?>"
download>

⬇ Download

</a>

</td>

<?php if($_SESSION['role']=='CEO') { ?>

<td>

<a
class="delete-btn"
href="upload_documents.php?id=<?php echo $employee_id; ?>&delete=<?php echo $doc['id']; ?>"
onclick="return confirm('Delete this document?')">

🗑 Delete

</a>

</td>

<?php } ?>

</tr>

<?php

}

?>

</table>

<br>

<?php if($_SESSION['role']=='CEO') { ?>

<a
class="back"
href="employees.php">

⬅ Back To Employees

</a>

<?php } else { ?>

<a
class="back"
href="employee_dashboard.php">

⬅ Back To Dashboard

</a>

<?php } ?>

</body>

</html>