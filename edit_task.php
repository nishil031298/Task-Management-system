<?php
session_start();
include '../db.php';

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$id = $_GET['id'] ?? 0;

// Fetch task
$query = "SELECT * FROM tasks WHERE id='$id' AND deleted_at IS NULL";
$result = mysqli_query($conn, $query);
$task = mysqli_fetch_assoc($result);

if(!$task){
    die("Task not found!");
}

// Update task
if(isset($_POST['update'])){

    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due = $_POST['due_date'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    // Admin vs User update
    if($role == 'admin'){
        $update = "UPDATE tasks SET 
                    title='$title',
                    description='$desc',
                    priority='$priority',
                    status='$status',
                    due_date='$due',
                    category='$category'
                   WHERE id='$id'";
    } else {
        $update = "UPDATE tasks SET 
                    title='$title',
                    description='$desc',
                    priority='$priority',
                    status='$status',
                    due_date='$due',
                    category='$category'
                   WHERE id='$id' AND user_id='$user_id'";
    }

    // Execute query
    if(mysqli_query($conn, $update)){
        header("Location: ../dashboard.php");
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Task</title>

<style>
body {
    font-family: Arial;
    background: #f4f4f4;
}

.container {
    width: 400px;
    margin: auto;
    background: white;
    padding: 20px;
    margin-top: 50px;
    border-radius: 10px;
}

input, textarea, select {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
}

button {
    background: #28a745;
    color: white;
    padding: 10px;
    border: none;
    width: 100%;
    cursor: pointer;
}
</style>

</head>
<body>

<div class="container">

<h2>Edit Task</h2>

<form method="POST">

<label>Title</label>
<input type="text" name="title" value="<?= $task['title'] ?>" required>

<label>Description</label>
<textarea name="desc"><?= $task['description'] ?></textarea>

<label>Priority</label>
<select name="priority">
    <option value="Low" <?= $task['priority']=='Low'?'selected':'' ?>>Low</option>
    <option value="Medium" <?= $task['priority']=='Medium'?'selected':'' ?>>Medium</option>
    <option value="High" <?= $task['priority']=='High'?'selected':'' ?>>High</option>
</select>

<label>Status</label>
<select name="status">
    <option value="Pending" <?= $task['status']=='Pending'?'selected':'' ?>>Pending</option>
    <option value="Completed" <?= $task['status']=='Completed'?'selected':'' ?>>Completed</option>
</select>

<label>Due Date</label>
<input type="date" name="due_date" value="<?= $task['due_date'] ?>">

<label>Category</label>
<input type="text" name="category" value="<?= $task['category'] ?>">

<button type="submit" name="update">Update Task</button>

</form>

<br>

<a href="../dashboard.php">⬅ Back</a>

</div>

</body>
</html>