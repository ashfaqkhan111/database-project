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



if(isset($_POST['return_book']))
{
    $borrow_id = $_POST['borrow_id'];
    $book_id = $_POST['book_id'];

    $borrow_query = mysqli_query(
        $conn,
        "
        SELECT *
        FROM borrowings
        WHERE borrow_id='$borrow_id'
        "
    );

    $borrow = mysqli_fetch_assoc($borrow_query);

    mysqli_query(
        $conn,
        "
        UPDATE borrowings
        SET
            status='Returned',
            return_date=CURDATE()
        WHERE borrow_id='$borrow_id'
        "
    );

    mysqli_query(
        $conn,
        "
        UPDATE books
        SET available_copies = available_copies + 1
        WHERE book_id='$book_id'
        "
    );

    $due_date = strtotime($borrow['due_date']);
    $today = strtotime(date('Y-m-d'));

    if($today > $due_date)
    {
        $days_late =
        ($today - $due_date) / (60 * 60 * 24);

        $fine_amount = $days_late * 10000;

        $check = mysqli_query(
            $conn,
            "
            SELECT *
            FROM fines
            WHERE borrow_id='$borrow_id'
            "
        );

        if(mysqli_num_rows($check) == 0)
        {
            mysqli_query(
                $conn,
                "
                INSERT INTO fines
                (
                    borrow_id,
                    amount,
                    paid_status
                )
                VALUES
                (
                    '$borrow_id',
                    '$fine_amount',
                    'Unpaid'
                )
                "
            );
        }

        $message =
        "Book Returned Successfully | Fine Created: $fine_amount";
    }
    else
    {
        $message =
        "Book Returned Successfully | No Fine Applied";
    }
}



if(isset($_GET['search']) && $_GET['search'] != "")
{
    $search = $_GET['search'];

    $sql = "
    SELECT
        br.borrow_id,
        m.member_code,
        m.member_name,
        b.book_id,
        b.title,
        b.isbn,
        br.borrow_date,
        br.due_date

    FROM borrowings br

    INNER JOIN members m
    ON br.member_id = m.member_id

    INNER JOIN books b
    ON br.book_id = b.book_id

    WHERE
        br.status='Borrowed'
        AND
        (
            m.member_code LIKE '%$search%'
            OR
            m.member_name LIKE '%$search%'
        )

    ORDER BY br.borrow_date DESC
    ";
}
else
{
    $sql = "
    SELECT
        br.borrow_id,
        m.member_code,
        m.member_name,
        b.book_id,
        b.title,
        b.isbn,
        br.borrow_date,
        br.due_date

    FROM borrowings br

    INNER JOIN members m
    ON br.member_id = m.member_id

    INNER JOIN books b
    ON br.book_id = b.book_id

    WHERE br.status='Borrowed'

    ORDER BY br.borrow_date DESC
    ";
}

$result = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Return Book</title>

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/pagespec.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="content">

    <?php
    if(isset($message))
    {
        echo "<div class='success-message'>$message</div>";
    }
    ?>

    <div class="form-box-search">

        <h2>Return Book</h2>

        <form class="search-form" method="GET">

            <input
            type="text"
            name="search"
            placeholder="Search Member Code or Member Name">

            <button type="submit">
                Search
            </button>

        </form>

    </div>

    <div class="table-container">

        <h2>Borrowed Books</h2>

        <table>

            <tr>
                <th>Borrow ID</th>
                <th>Member Code</th>
                <th>Member Name</th>
                <th>Book Title</th>
                <th>ISBN</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Action</th>
            </tr>

            <?php

            if(mysqli_num_rows($result) > 0)
            {
                while($row = mysqli_fetch_assoc($result))
                {
            ?>

            <tr>

                <td><?php echo $row['borrow_id']; ?></td>

                <td><?php echo $row['member_code']; ?></td>

                <td><?php echo $row['member_name']; ?></td>

                <td><?php echo $row['title']; ?></td>

                <td><?php echo $row['isbn']; ?></td>

                <td><?php echo $row['borrow_date']; ?></td>

                <td><?php echo $row['due_date']; ?></td>

                <td>

                    <form method="POST">

                        <input
                        type="hidden"
                        name="borrow_id"
                        value="<?php echo $row['borrow_id']; ?>">

                        <input
                        type="hidden"
                        name="book_id"
                        value="<?php echo $row['book_id']; ?>">

                        <button type="submit" name="return_book">
    <i class="fa-solid fa-arrow-left"></i>
    Return Book
</button>

                    </form>

                </td>

            </tr>

            <?php
                }
            }
            else
            {
                echo "
                <tr>
                    <td colspan='8'>
                        No borrowed books found
                    </td>
                </tr>
                ";
            }
            ?>

        </table>

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