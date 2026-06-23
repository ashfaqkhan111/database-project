<?php

include 'db.php';

$fine = null;

if(isset($_POST['update_fine']))
{
    $fine_id = $_POST['fine_id'];
    $paid_status = $_POST['paid_status'];

    $sql = "
    UPDATE fines
    SET paid_status='$paid_status'
    WHERE fine_id='$fine_id'
    ";

    mysqli_query($conn,$sql);

    header("Location: update_fine.php?id=$fine_id&updated=1");
    exit();
}

if(isset($_GET['id']))
{
    $fine_id = $_GET['id'];

    $sql = "
    SELECT
        f.fine_id,
        f.amount,
        f.paid_status,
        m.member_code,
        m.member_name,
        b.title

    FROM fines f

    INNER JOIN borrowings br
        ON f.borrow_id = br.borrow_id

    INNER JOIN members m
        ON br.member_id = m.member_id

    INNER JOIN books b
        ON br.book_id = b.book_id

    WHERE f.fine_id='$fine_id'
    ";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0)
    {
        $fine = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Update Fine Status</title>

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/pagespec.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="content">

    <h2>Update Fine Status</h2>

    <?php
    if(isset($_GET['updated']))
    {
        echo "
        <div class='success-message'>
            Fine Status Updated Successfully
        </div>
        ";
    }
    ?>

    <form class="search-form" method="GET">

        <input
        type="text"
        name="search"
        placeholder="Fine ID, Member Code or Member Name"
        required>

        <button type="submit">
            Search
        </button>

    </form>

    <?php

    if(isset($_GET['search']))
    {
        $search = $_GET['search'];

        $sql = "
        SELECT
            f.fine_id,
            f.amount,
            f.paid_status,
            m.member_code,
            m.member_name,
            b.title

        FROM fines f

        INNER JOIN borrowings br
            ON f.borrow_id = br.borrow_id

        INNER JOIN members m
            ON br.member_id = m.member_id

        INNER JOIN books b
            ON br.book_id = b.book_id

        WHERE
            f.fine_id LIKE '%$search%'
            OR m.member_code LIKE '%$search%'
            OR m.member_name LIKE '%$search%'

        ORDER BY f.fine_id DESC
        ";

        $result = mysqli_query($conn,$sql);

        if(mysqli_num_rows($result) > 0)
        {
        ?>

        <div class="table-container">

            <h2>Search Results</h2>

            <table>

                <tr>
                    <th>Fine ID</th>
                    <th>Member Code</th>
                    <th>Member Name</th>
                    <th>Book Title</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php
                while($row = mysqli_fetch_assoc($result))
                {
                ?>

                <tr>

                    <td><?php echo $row['fine_id']; ?></td>

                    <td><?php echo $row['member_code']; ?></td>

                    <td><?php echo $row['member_name']; ?></td>

                    <td><?php echo $row['title']; ?></td>

                    <td>Rp <?php echo number_format($row['amount'],0,',','.'); ?></td>

                    <td><?php echo $row['paid_status']; ?></td>

                    <td>

                        <a
                        class="select-btn"
                        href="update_fine.php?id=<?php echo $row['fine_id']; ?>">

                            Select

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
                No Fine Records Found
            </div>
            ";
        }
    }
    ?>

    <?php if($fine){ ?>

    <div class="form-box">

        <h2>Fine Details</h2>

        <table>

            <tr>
                <td><strong>Fine ID</strong></td>
                <td><?php echo $fine['fine_id']; ?></td>
            </tr>

            <tr>
                <td><strong>Member Code</strong></td>
                <td><?php echo $fine['member_code']; ?></td>
            </tr>

            <tr>
                <td><strong>Member Name</strong></td>
                <td><?php echo $fine['member_name']; ?></td>
            </tr>

            <tr>
                <td><strong>Book Title</strong></td>
                <td><?php echo $fine['title']; ?></td>
            </tr>

               <tr>
                <td><strong>Amount</strong></td>
                <td>Rp <?php echo number_format($fine['amount'],0,',','.'); ?></td>
                </tr>

        </table>

        <hr>

        <form method="POST">

            <input
            type="hidden"
            name="fine_id"
            value="<?php echo $fine['fine_id']; ?>">

            <label>Paid Status</label>

            <select name="paid_status">

                <option
                value="Paid"
                <?php if($fine['paid_status']=="Paid") echo "selected"; ?>>
                    Paid
                </option>

                <option
                value="Unpaid"
                <?php if($fine['paid_status']=="Unpaid") echo "selected"; ?>>
                    Unpaid
                </option>

            </select>

           <button type="submit" name="update_fine">
    <i class="fa-solid fa-pen-to-square"></i>
    Update Fine
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

