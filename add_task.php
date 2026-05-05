<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../db.php';

// Check login
if(!isset($_SESSION['user_id'])){
    die("User not logged in");
}

if(isset($_POST['add'])){
    $uid = $_SESSION['user_id'];

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['desc']);
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due = $_POST['due_date'];
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $query = "INSERT INTO tasks(user_id,title,description,status,priority,due_date,category)
              VALUES('$uid','$title','$desc','$status','$priority','$due','$category')";

    if(mysqli_query($conn, $query)){
        header("Location: ../dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: 600;
            font-size: 14px;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102,126,234,0.5);
        }

        textarea {
            resize: none;
            height: 80px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5a67d8;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>➕ Add New Task</h2>

    <form method="POST">
        
        <label>Title</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="desc" required></textarea>

        <label>Priority</label>
        <select name="priority" required>
            <option value="Low">🟢 Low</option>
            <option value="Medium">🟡 Medium</option>
            <option value="High">🔴 High</option>
        </select>

        <label>Status</label>
        <select name="status">
            <option value="Pending">⏳ Pending</option>
            <option value="Completed">✅ Completed</option>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date" required>

        <label>Category</label>
        <input type="text" name="category" placeholder="Work / Study / Personal" required>

        <button type="submit" name="add">Add Task</button>
    </form>

    <a href="../dashboard.php" class="back-link">⬅ Back to Dashboard</a>
</div>

</body>
</html>