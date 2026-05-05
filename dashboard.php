<?php
session_start();
include 'db.php';

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

/* =========================
   TASK QUERY
========================= */
if($role == 'admin'){
    $tasks = mysqli_query($conn,"
        SELECT tasks.*, users.name 
        FROM tasks 
        JOIN users ON tasks.user_id = users.id
        WHERE users.deleted_at IS NULL
        AND tasks.deleted_at IS NULL
    ");
} else {
    $tasks = mysqli_query($conn,"
        SELECT * FROM tasks 
        WHERE user_id='$user_id'
        AND deleted_at IS NULL
    ");
}

/* =========================
   USERS QUERY (ADMIN ONLY)
========================= */
if($role == 'admin'){
    $users = mysqli_query($conn,"
        SELECT * FROM users 
        WHERE deleted_at IS NULL
    ");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            background: #e74c3c;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .container {
            padding: 30px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 15px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .role {
            background: #2ecc71;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-bottom: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th {
            background: #34495e;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        .action a {
            padding: 6px 10px;
            margin: 2px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
        }

        .edit { background: #27ae60; }
        .delete { background: #e74c3c; }

        h3 {
            margin-top: 40px;
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>Task Dashboard</h2>
    <div>
        <span class="role"><?= strtoupper($role) ?></span>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <!-- ADD TASK BUTTON -->
    <div class="top-bar">
        <?php if($role == 'user'){ ?>
            <a href="user/add_task.php" class="btn">+ Add Task</a>
        <?php } ?>
    </div>

    <!-- =========================
         TASK TABLE
    ========================== -->
    <h3>Tasks</h3>

    <table>
        <tr>
            <?php if($role == 'admin'){ ?>
                <th>User</th>
            <?php } ?>
            <th>Title</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Due Date</th>
            <th>Description</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($tasks)){ ?>
        <tr>
            <?php if($role == 'admin'){ ?>
                <td><?= htmlspecialchars($row['name']) ?></td>
            <?php } ?>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['priority']) ?></td>
            <td><?= htmlspecialchars($row['due_date']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td class="action">
                <a href="user/edit_task.php?id=<?= $row['id'] ?>" class="edit">Edit</a>
                <a href="user/delete_task.php?id=<?= $row['id'] ?>" 
                   class="delete"
                   onclick="return confirm('Delete this task?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <!-- =========================
         USERS TABLE (ADMIN ONLY)
    ========================== -->
    <?php if($role == 'admin'){ ?>

    <h3>Users</h3>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while($user = mysqli_fetch_assoc($users)){ ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td class="action">
                <!-- ✅ FIXED HERE -->
                <a href="user/user_edit.php?id=<?= $user['id'] ?>" class="edit">Edit</a>

                <a href="user/user_delete.php?id=<?= $user['id'] ?>" 
                   class="delete"
                   onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
        <?php } ?>

    </table>

    <?php } ?>

</div>

</body>
</html>