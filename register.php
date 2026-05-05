<?php
include 'db.php';

$message = "";

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($name) || empty($email) || empty($password)){
    $message = "All fields are required!";
} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $message = "Invalid email format!";
} elseif(strlen($password) < 6){
    $message = "Password must be at least 6 characters!";
} else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepared statement
        $stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if($stmt->execute()){
            $message = "Registration successful!";
        } else {
            $message = "Email already exists!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body{
    font-family: Arial;
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.container{
    background: rgba(255,255,255,0.1);
    padding: 30px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    width: 320px;
    color: white;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}

h2{
    text-align:center;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:8px;
}

button{
    width:100%;
    padding:10px;
    background:#ff7b00;
    border:none;
    color:white;
    font-weight:bold;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#ff5500;
}

.message{
    text-align:center;
    margin-bottom:10px;
    color:yellow;
}
</style>
</head>

<body>

<div class="container">
    <h2>Create Account</h2>

    <?php if($message != ""){ ?>
        <div class="message"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="register">Register</button>

        <p style="text-align:center; margin-top:10px;">
    Back to login
    <a href="index.php" style="color:#fff; font-weight:bold;">Login here</a>
</p>
    </form>
</div>

</body>
</html>