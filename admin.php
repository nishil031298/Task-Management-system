<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

// Check admin role
if($_SESSION['role'] != 'admin'){
    die("Access denied! Admin only.");
}

// Fetch all tasks with user info
$query = "SELECT tasks.*, users.name 
          FROM tasks 
          JOIN users ON tasks.user_id = users.id";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - All Tasks</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; }
        table { border-collapse: collapse; width: 100%; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #333; color: white; }
        h2 { text-align: center; }
        a { padding: 5px 10px; background: red; color: white; text-decoration: none; }
    </style>
</head>
<body>

<h2>Admin Panel - All User Tasks</h2>

<a href="../dashboard.php">⬅ Back to Dashboard</a>
<a href="../logout.php" style="float:right;">Logout</a>

<br><br>

<table>
<tr>
<th>User</th>
<th>Title</th>
<th>Description</th>
<th>Priority</th>
<th>Status</th>
<th>Due Date</th>
<th>Category</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $row['name'] ?></td>
<td><?= $row['title'] ?></td>
<td><?= $row['description'] ?></td>
<td><?= $row['priority'] ?></td>
<td><?= $row['status'] ?></td>
<td><?= $row['due_date'] ?></td>
<td><?= $row['category'] ?></td>
<td>
<a href="../user/edit_task.php?id=<?= $row['id'] ?>">Edit</a>
<a href="../user/delete_task.php?id=<?= $row['id'] ?>" 
onclick="return confirm('Delete this task?')">Delete</a>

</td>
</tr>
<?php } ?>

</table>

</body>
</html>