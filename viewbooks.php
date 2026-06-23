<?php
session_start();



error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

include 'db.php';

$search = "";

$sql = "
SELECT
    b.book_id,
    b.title,
    b.isbn,
    b.publication_year,
    b.available_copies,
    a.author_name,
    c.category_name,
    p.publisher_name
FROM books b
INNER JOIN authors a ON b.author_id = a.author_id
INNER JOIN categories c ON b.category_id = c.category_id
INNER JOIN publishers p ON b.publisher_id = p.publisher_id
";

if(isset($_GET['search']) && !empty($_GET['search']))
{
    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $sql .= "
    WHERE
        b.title LIKE '%$search%'
        OR b.isbn LIKE '%$search%'
    ";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Books</title>
    <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/tables.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/pagespec.css">
        <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="view-books-page">

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
    <div class="content">

        <h2>All Books</h2>

        <div class="search-box">

    <form method="GET">

        <input
        type="text"
        name="search"
        placeholder="Search by Title or ISBN"
        value="<?php echo $search; ?>">

        <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
            Search
        </button>

    </form>

</div>

        <table border="1">

            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Publication Year</th>
                <th>Available Copies</th>
                <th>Author</th>
                <th>Category</th>
                <th>Publisher</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($result)) { ?>

                <tr>
                    <td><?php echo $row['book_id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['isbn']; ?></td>
                    <td><?php echo $row['publication_year']; ?></td>
                    <td><?php echo $row['available_copies']; ?></td>
                    <td><?php echo $row['author_name']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['publisher_name']; ?></td>
                </tr>

            <?php } ?>

        </table>

    </div>

    <script>

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

    </script>

</body>

</html>