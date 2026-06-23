<?php
include 'db.php';

if(isset($_POST['register_member'])){
    $member_name = $_POST['member_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];

$sql = "INSERT INTO members (member_name,email,phone,address,gender,registration_date)
VALUES ('$member_name','$email','$phone','$address','$gender',CURDATE())";

mysqli_query($conn,$sql);
$member_id = mysqli_insert_id($conn);
$member_code = "M" . str_pad($member_id,5,"0",STR_PAD_LEFT);

$update = "UPDATE members SET member_code ='$member_code' where member_id = '$member_id'";

mysqli_query($conn,$update);

echo "<h3>Member Registerd Successfully</h3>";
echo "Member Code: $member_code </h3>";
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>
            Registration
        </title>
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/pagespec.css">
        <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        </head>
        <body>
            <div class="content">


            <div class="form-box">
            <h1>Register Member</h1>

            <form method="post">
                <label for="member_name">Name</label>
           
                <input type="text" name="member_name" id="member_name" placeholder="jhon" required>
          

                <label for="email">Email</label>
              
                <input type="email" name="email" id="email" placeholder="abc@example.com" required>
             
                <label for="phone">Phone</label>
               
                <input type="text" name="phone" placeholder="0988700988" required>
                
                <label for="address"> Address</label>
            
                <input type="text" name="address" id="address" placeholder="address" required>
               
                <label for="gender">Gender</label>
                
                <select name="gender">
                    <option value="male">
                        Male
                    </option>
                    <option value="female">
                        Female
                    </option>
                </select>
                <br><br>
                <button type="submit" name="register_member">register</button>
            </form>

</div>
</div>



 <div class="sidebar">

    <!-- <p class="welcome-message">
        <i class="fa-solid fa-user"></i>
        Welcome, <?php echo $_SESSION['librarian_name']; ?>
    </p> -->

    <h2>
        <a href="dashboard.php">
            <i class="fa-solid fa-book-open"></i>
            Library Management
        </a>
    </h2>

    <button class="drop-menu">
        <i class="fa-solid fa-book"></i>
        Manage Books
    </button>

    <div class="dropdown-content">
        <a href="addbook.php">
            <i class="fa-solid fa-plus"></i>
            Add Book
        </a>

        <a href="viewbooks.php">
            <i class="fa-solid fa-book-open-reader"></i>
            View Books
        </a>

        <a href="edit_book.php">
            <i class="fa-solid fa-pen-to-square"></i>
            Edit Books
        </a>

        <a href="borrow_book.php">
            <i class="fa-solid fa-arrow-right"></i>
            Borrow a Book
        </a>

        <a href="returnbook.php">
            <i class="fa-solid fa-arrow-left"></i>
            Return a Book
        </a>

        <a href="author_publisher.php">
            <i class="fa-solid fa-user-pen"></i>
            Authors & Publishers
        </a>
    </div>

    <button class="drop-menu">
        <i class="fa-solid fa-users"></i>
        Manage Members
    </button>

    <div class="dropdown-content">
        <a href="registration.php">
            <i class="fa-solid fa-user-plus"></i>
            Register Member
        </a>

        <a href="editmember.php">
            <i class="fa-solid fa-user-gear"></i>
            View & Edit Members
        </a>
    </div>

    <button class="drop-menu">
        <i class="fa-solid fa-money-bill-wave"></i>
        Fine Management
    </button>

    <div class="dropdown-content">
        <a href="viewfines.php">
            <i class="fa-solid fa-receipt"></i>
            View Fine
        </a>

        <a href="update_fine.php">
            <i class="fa-solid fa-file-pen"></i>
            Update Fine
        </a>
    </div>

    <a class="menu-link" href="index.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        Logout
    </a>

</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    let dropdowns = document.getElementsByClassName("drop-menu");

    for (let i = 0; i < dropdowns.length; i++) {

        dropdowns[i].addEventListener("click", function () {

            let menu = this.nextElementSibling;

            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }

        });

    }

});
</script>
        </body>
    
</html>