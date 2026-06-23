<?php


session_start();

if (!isset($_SESSION['librarian_id']))
{
    header("Location: index.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$member = null;


if(isset($_GET['activate']))
{
    $id = $_GET['activate'];

    mysqli_query(
        $conn,
        "
        UPDATE members
        SET status='Active'
        WHERE member_id='$id'
        "
    );

    echo "
    <div class='success-message'>
        Member Activated Successfully
    </div>";
}


if(isset($_GET['deactivate']))
{
    $id = $_GET['deactivate'];

    mysqli_query(
        $conn,
        "
        UPDATE members
        SET status='Inactive'
        WHERE member_id='$id'
        "
    );

    echo "
    <div class='success-message'>
        Member Deactivated Successfully
    </div>";
}



if(isset($_POST['update_member']))
{
    $member_id = $_POST['member_id'];
    $member_name = $_POST['member_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];

    $sql = "
    UPDATE members
    SET
        member_name='$member_name',
        email='$email',
        phone='$phone',
        address='$address',
        gender='$gender',
        status='$status'
    WHERE member_id='$member_id'
    ";

    if(mysqli_query($conn,$sql))
    {
        header("Location: editmember.php?id=$member_id&updated=1");
        exit();
    }
}



if(isset($_GET['delete']))
{
    $member_id = $_GET['delete'];

    $check_sql = "
    SELECT *
    FROM borrowings
    WHERE member_id='$member_id'
    ";

    $check_result = mysqli_query($conn,$check_sql);

    if(mysqli_num_rows($check_result) > 0)
    {
        echo "
        <div class='error-message'>
            Member has borrowing history.
            Change status to Inactive instead.
        </div>";
    }
    else
    {
        mysqli_query(
            $conn,
            "DELETE FROM members
             WHERE member_id='$member_id'"
        );

        echo "
        <div class='success-message'>
            Member Deleted Successfully
        </div>";
    }
}



if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $sql = "
    SELECT *
    FROM members
    WHERE member_id='$id'
    ";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0)
    {
        $member = mysqli_fetch_assoc($result);
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Member</title>

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/pagespec.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="content">

    <h2>Manage Member</h2>

    <?php
    if(isset($_GET['updated']))
    {
        echo "
        <div class='success-message'>
            Member Updated Successfully
        </div>";
    }
    ?>

    <form class="search-form" method="GET">

        <input
        type="text"
        name="search"
        placeholder="Member Code or Member Name">

        <button type="submit">
            Search
        </button>

    </form>

    <?php

    if(isset($_GET['search']))
    {
        $search = $_GET['search'];

        $sql = "
        SELECT *
        FROM members
        WHERE
            member_code LIKE '%$search%'
            OR member_name LIKE '%$search%'
        ";

        $result = mysqli_query($conn,$sql);

        if(mysqli_num_rows($result) > 0)
        {
        ?>

        <div class="table-container">

    <table>

        <tr>
            <th>ID</th>
            <th>Member Code</th>
            <th>Member Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Deactivate</th>
            <th>Activate</th>
            <th>Delete</th>
        </tr>

        <?php
        while($row = mysqli_fetch_assoc($result))
        {
        ?>

        <tr>

    <td><?php echo $row['member_id']; ?></td>

    <td><?php echo $row['member_code']; ?></td>

    <td><?php echo $row['member_name']; ?></td>

    <td><?php echo $row['email']; ?></td>

    <td><?php echo $row['phone']; ?></td>

    <td><?php echo $row['status']; ?></td>

    <td>
    <a href="editmember.php?id=<?php echo $row['member_id']; ?>">
        Edit
    </a>
</td>

<td>
    <a href="editmember.php?deactivate=<?php echo $row['member_id']; ?>">
        Deactivate
    </a>
</td>

<td>
    <a href="editmember.php?activate=<?php echo $row['member_id']; ?>">
        Activate
    </a>
</td>

<td>
    <a
    href="editmember.php?delete=<?php echo $row['member_id']; ?>"
    onclick="return confirm('Delete this member?');">
        Delete
    </a>
</td>

</tr>

        <?php
        }
        ?>

    </table>

</div>

        <?php
        }
        else
        {
            echo "
            <div class='error-message'>
                No Members Found
            </div>";
        }
    }
    ?>

    <?php if($member !== null){ ?>

    <div class="form-box">

        <h2>Edit Member</h2>

        <form method="POST">

            <input
            type="hidden"
            name="member_id"
            value="<?php echo $member['member_id']; ?>">

            <label>Member Code</label>

            <input
            type="text"
            value="<?php echo $member['member_code']; ?>"
            readonly>

            <label>Member Name</label>

            <input
            type="text"
            name="member_name"
            value="<?php echo $member['member_name']; ?>"
            required>

            <label>Email</label>

            <input
            type="email"
            name="email"
            value="<?php echo $member['email']; ?>">

            <label>Phone</label>

            <input
            type="text"
            name="phone"
            value="<?php echo $member['phone']; ?>">

            <label>Address</label>

            <input
            type="text"
            name="address"
            value="<?php echo $member['address']; ?>">

            <label>Gender</label>

            <select name="gender">

                <option
                value="Male"
                <?php if($member['gender']=="Male") echo "selected"; ?>>
                    Male
                </option>

                <option
                value="Female"
                <?php if($member['gender']=="Female") echo "selected"; ?>>
                    Female
                </option>

            </select>

            <label>Status</label>

                    <select name="status">

                        <option
                        value="Active"
                        <?php if($member['status']=="Active") echo "selected"; ?>>
                            Active
                        </option>

                        <option
                        value="Inactive"
                        <?php if($member['status']=="Inactive") echo "selected"; ?>>
                            Inactive
                        </option>

                    </select>

           <button type="submit" name="update_member">
    <i class="fa-solid fa-pen-to-square"></i>
    Update
</button>

                        

        </form>

    </div>

    <?php } ?>

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

