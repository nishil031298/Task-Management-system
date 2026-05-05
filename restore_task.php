<?php
include '../db.php';

$id = $_GET['id'];

mysqli_query($conn, "
    UPDATE tasks 
    SET deleted_at = NULL 
    WHERE id = '$id'
");

header("Location: ../dashboard.php");
?>