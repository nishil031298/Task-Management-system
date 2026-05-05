<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "school";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$createUser="CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','user') DEFAULT 'user' 
)";

mysqli_query($conn, $createUser);



$createTable="CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    description TEXT,
    status ENUM('Pending','Completed') DEFAULT 'Pending',
    priority ENUM('Low','Medium','High'),
    due_date DATE,
    category VARCHAR(100),
    
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    
)";
mysqli_query($conn, $createTable);

$sql = "UPDATE users SET role='admin' WHERE email='nishil03@gmail.com'";

// if(mysqli_query($conn, $sql)){
//     echo "User updated to admin successfully";
// } 
// else {
//     echo "Error: " . mysqli_error($conn);
// }

?>