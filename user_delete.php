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

// Check ID
if(!isset($_GET['id'])){
    die("Task ID missing");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

/* =========================
   DELETE LOGIC (SECURE)
========================= */
if($role == 'admin'){
    // Admin can delete any task
    $query = "UPDATE tasks SET deleted_at = NOW() WHERE id='$id'";
} else {
    // User can delete only their own task
    $query = "UPDATE tasks 
              SET deleted_at = NOW() 
              WHERE id='$id' AND user_id='$user_id'";
}

$result = mysqli_query($conn, $query);

// Check error
if(!$result){
    die("Error: " . mysqli_error($conn));
}

// Check if any row affected
if(mysqli_affected_rows($conn) == 0){
    die("Task not found or permission denied");
}

// Redirect
header("Location: ../dashboard.php");
exit();
?>