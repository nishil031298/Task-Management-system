<?php
session_start();
include '../db.php';

if(!isset($_GET['id'])){
    die("Task ID missing");
}

$id = $_GET['id'];


$query = "UPDATE tasks SET deleted_at = NOW() WHERE id = '$id'";

if(mysqli_query($conn, $query)){
    header("Location: ../dashboard.php");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>