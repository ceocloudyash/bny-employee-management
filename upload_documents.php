<?php

ob_start();
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';

/* CREATE FOLDERS */

if (!is_dir("uploads")) {
    mkdir("uploads", 0777, true);
}

if (!is_dir("uploads/documents")) {
    mkdir("uploads/documents", 0777, true);
}

/* GET EMPLOYEE ID */

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $employee_id = $_GET['id'];

} elseif (
    isset($_SESSION['role']) &&
    $_SESSION['role'] == "EMPLOYEE" &&
    isset($_SESSION['employee_id'])
) {

    $employee_id = $_SESSION['employee_id'];

} else {

    die("Employee ID Not Found");
}

$message = "";

/* UPLOAD DOCUMENT */

if (isset($_POST['upload'])) {

    $document_name = trim($_POST['document_name']);
    $document_type = trim($_POST['document_type']);

    if (
        isset($_FILES['document']) &&
        $_FILES['document']['error'] == 0
    ) {

        $file_name =
            time() . "_" .
            preg_replace(
                "/[^a-zA-Z0-9._-]/",
                "_",
                $_FILES['document']['name']
            );

        $target =
            "uploads/documents/" .
            $file_name;

        if (
            move_uploaded_file(
                $_FILES['document']['tmp_name'],
                $target
            )
        ) {

            $stmt = $conn->prepare(
                "INSERT INTO employee_documents
                (
                    employee_id,
                    document_name,
                    document_type,
                    file_name
                )
                VALUES
                (
                    ?, ?, ?, ?
                )"
            );

            $stmt->bind_param(
                "ssss",
                $employee_id,
                $document_name,
                $document_type,
                $file_name
            );

            if ($stmt->execute()) {

                $message =
                "✅ Document Uploaded Successfully";

            } else {

                $message =
                "❌ Database Insert Failed";
            }

            $stmt->close();

        } else {

            $message =
            "❌ File Upload Failed";
        }

    } else {

        $message =
        "❌ Please Select A File";
    }
}

/* DELETE DOCUMENT */

if (
    isset($_GET['delete']) &&
    $_SESSION['role'] == "CEO"
) {

    $delete_id = intval($_GET['delete']);

    $stmt = $conn->prepare(
        "SELECT file_name
        FROM employee_documents
        WHERE id=?"
    );

    $stmt->bind_param(
        "i",
        $delete_id
    );

    $stmt->execute();

    $result =
    $stmt->get_result();

    if ($result->num_rows > 0) {

        $doc =
        $result->fetch_assoc();

        $filepath =
        "uploads/documents/" .
        $doc['file_name'];

        if (file_exists($filepath)) {

            unlink($filepath);
        }

        $deleteStmt =
        $conn->prepare(
            "DELETE FROM employee_documents
            WHERE id=?"
        );

        $deleteStmt->bind_param(
            "i",
            $delete_id
        );

        $deleteStmt->execute();
        $deleteStmt->close();
    }

    header(
        "Location: upload_documents.php?id=" .
        urlencode($employee_id)
    );

    exit();
}

/* FETCH DOCUMENTS */

$stmt = $conn->prepare(
    "SELECT *
    FROM employee_documents
    WHERE employee_id=?
    ORDER BY id DESC"
);

$stmt->bind_param(
    "s",
    $employee_id
);

$stmt->execute();

$documents =
$stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

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
margin-bottom:20px;
}

.card{
background:#1e293b;
padding:20px;
border-radius:15px;
margin-bottom:20px;
}

input,
select{
width:100%;
padding:12px;
margin:10px 0;
border:none;
border-radius:8px;
background:#0f172a;
color:white;
box-sizing:border-box;
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

.success{
background:#14532d;
padding:12px;
border-radius:8px;
margin-bottom:15px;
}

table{
width:100%;
border-collapse:collapse;
background:#1e293b;
border-radius:12px;
overflow:hidden;
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

.download{
color:#22d3ee;
text-decoration:none;
}

.delete{
color:#ef4444;
text-decoration:none;
}

.back{
display:inline-block;
margin-top:20px;
padding:10px 15px;
background:#06b6d4;
color:white;
text-decoration:none;
border-radius:8px;
}

</style>

</head>

<body>

<h1>📄 Employee Documents</h1>

<?php
if (!empty($message)) {
    echo "<div class='success'>$message</div>";
}
?>

<div class="card">

<form method="POST" enctype="multipart/form-data">

<select
name="document_type"
required>

<option value="">
Select Document Type
</option>

<option value="Resume">Resume</option>
<option value="PAN Card">PAN Card</option>
<option value="Aadhaar Card">Aadhaar Card</option>
<option value="Certificate">Certificate</option>
<option value="Offer Letter">Offer Letter</option>
<option value="Other">Other</option>

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

📤 Upload Document

</button>

</form>

</div>

<table>

<tr>

<th>ID</th>
<th>Type</th>
<th>Name</th>
<th>Download</th>

<?php if ($_SESSION['role'] == "CEO") { ?>
<th>Delete</th>
<?php } ?>

</tr>

<?php

if ($documents && $documents->num_rows > 0) {

    while ($doc = $documents->fetch_assoc()) {

?>

<tr>

<td><?php echo $doc['id']; ?></td>

<td><?php echo htmlspecialchars($doc['document_type']); ?></td>

<td><?php echo htmlspecialchars($doc['document_name']); ?></td>

<td>

<a
class="download"
href="uploads/documents/<?php echo urlencode($doc['file_name']); ?>"
download>

⬇ Download

</a>

</td>

<?php if ($_SESSION['role'] == "CEO") { ?>

<td>

<a
class="delete"
href="upload_documents.php?id=<?php echo urlencode($employee_id); ?>&delete=<?php echo $doc['id']; ?>"
onclick="return confirm('Delete this document?')">

🗑 Delete

</a>

</td>

<?php } ?>

</tr>

<?php

    }

} else {

?>

<tr>

<td colspan="5" style="text-align:center;">
No Documents Uploaded
</td>

</tr>

<?php

}

?>

</table>

<br>

<?php if ($_SESSION['role'] == "CEO") { ?>

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