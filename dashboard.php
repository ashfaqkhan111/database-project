<?php
session_start();

if (!isset($_SESSION['librarian_id']))
{
    header("Location: index.php");
    exit();
}

include 'db.php';

/* Dashboard Statistics */

$total_books = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM books"
    )
);

$total_members = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM members"
    )
);

$total_borrowed = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM borrowings
         WHERE status='Borrowed'"
    )
);

$total_returned = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM borrowings
         WHERE status='Returned'"
    )
);

$sql = "
SELECT
    br.borrow_id,
    m.member_code,
    m.member_name,
    b.title,
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

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/pagespec.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        .dashboard-cards{
            display:flex;
            gap:20px;
            margin-bottom:30px;
            flex-wrap:wrap;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            min-width:200px;
            text-align:center;
        }

        .card h2{
            margin:0;
            font-size:32px;
        }

        .card p{
            margin-top:10px;
            font-weight:bold;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <p class="welcome-message">
        <i class="fa-solid fa-user"></i>
        Welcome, <?php echo $_SESSION['librarian_name']; ?>
    </p>

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

<div class="content-dash">
    <div class="content-dash-container">

    <h1>Dashboard</h1>

    <div class="dashboard-cards">

        <div class="card">
            <h2><?php echo $total_books['total']; ?></h2>
            <p>Total Books</p>
        </div>

        <div class="card">
            <h2><?php echo $total_members['total']; ?></h2>
            <p>Total Members</p>
        </div>

        <div class="card">
            <h2><?php echo $total_borrowed['total']; ?></h2>
            <p>Borrowed Books</p>
        </div>

        <div class="card">
            <h2><?php echo $total_returned['total']; ?></h2>
            <p>Returned Books</p>
        </div>

    </div>

    <div class="table-container">

        <h2>Currently Borrowed Books</h2>

        <table>

            <tr>
                <th>Borrow ID</th>
                <th>Member Code</th>
                <th>Member Name</th>
                <th>Book Title</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
            </tr>

            <?php
            while($row = mysqli_fetch_assoc($result))
            {
            ?>
                <tr>

                    <td><?php echo $row['borrow_id']; ?></td>

                    <td><?php echo $row['member_code']; ?></td>

                    <td><?php echo $row['member_name']; ?></td>

                    <td><?php echo $row['title']; ?></td>

                    <td><?php echo $row['borrow_date']; ?></td>

                    <td><?php echo $row['due_date']; ?></td>

                </tr>
            <?php
            }
            ?>

        </table>

    </div>

</div>
</div>

<script>

let dropdowns = document.getElementsByClassName("drop-menu");

for (let i = 0; i < dropdowns.length; i++)
{
    dropdowns[i].addEventListener("click", function ()
    {
        let menu = this.nextElementSibling;

        if (menu.style.display === "block")
        {
            menu.style.display = "none";
        }
        else
        {
            menu.style.display = "block";
        }
    });
}

</script>

</body>
</html>