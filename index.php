<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $librarian_code = mysqli_real_escape_string($conn, $_POST['lid']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['pass']);

    $sql = "SELECT * FROM librarians
            WHERE librarian_code='$librarian_code'
            AND librarian_name='$name'
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        $_SESSION['librarian_id'] = $row['librarian_id'];
        $_SESSION['librarian_name'] = $row['librarian_name'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid Librarian Code, Name, or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/global.css">
    <link  rel="stylesheet" href="css/pagespec.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<style>
    body{
         background-image: url("images/background.jpg");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
    }
</style>



    <div class="form-box">
<h1>Login</h1>
    
    <form class="login-form" method="POST" action="">   

        <div>
            <label for="lid">Librarian Code</label>
            
            <input type="text" name="lid" id="lid"
                   placeholder="LIB000000" required>
        </div>

        <div>
            <label for="name">Name</label>
            
            <input type="text" name="name" id="name"
                   placeholder="John" required>
        </div>

        <div>
            <label for="pass">Password</label>
            
            <input type="password" name="pass"
                   id="pass" placeholder="********" required>
        </div>
               
       <button type="submit" name="login" class="login-btn">
    <i class="fa-solid fa-right-to-bracket"></i>
    Login
</button>

        <?php
        if (!empty($error)) {
            echo "<p style='color:red;'>$error</p>";
        }
        ?>

    </form>
</div>

</body>
</html>