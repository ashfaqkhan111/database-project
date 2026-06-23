
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

$book = null;



if(isset($_POST['borrow_book']))
{
    $book_id = $_POST['book_id'];
    $member_code = $_POST['member_code'];
    $due_date = $_POST['due_date'];

    $member_sql = "
    SELECT *
    FROM members
    WHERE member_code='$member_code'
    ";

    $member_result = mysqli_query($conn,$member_sql);

    if(mysqli_num_rows($member_result) > 0)
    {
        $member = mysqli_fetch_assoc($member_result);

        $member_id = $member['member_id'];

        $book_sql = "
        SELECT *
        FROM books
        WHERE book_id='$book_id'
        ";

        $book_result = mysqli_query($conn,$book_sql);

        $book_data = mysqli_fetch_assoc($book_result);

        if($book_data['available_copies'] > 0)
        {
            $sql = "
            INSERT INTO borrowings
            (
                borrow_date,
                due_date,
                status,
                member_id,
                book_id
            )
            VALUES
            (
                CURDATE(),
                '$due_date',
                'Borrowed',
                '$member_id',
                '$book_id'
            )
            ";

            mysqli_query($conn,$sql);

            mysqli_query(
                $conn,
                "
                UPDATE books
                SET available_copies = available_copies - 1
                WHERE book_id='$book_id'
                "
            );

            echo "<h3 class='success-message'>
            Book Borrowed Successfully
            </h3>";
        }
        else
        {
            echo "<h3 class='error-message'>
            No Available Copies
            </h3>";
        }
    }
    else
    {
        echo "<h3 class='error-message'>
        Member Code Not Found
        </h3>";
    }
}



if(isset($_GET['book_id']))
{
    $book_id = $_GET['book_id'];

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

    INNER JOIN authors a
    ON b.author_id = a.author_id

    INNER JOIN categories c
    ON b.category_id = c.category_id

    INNER JOIN publishers p
    ON b.publisher_id = p.publisher_id

    WHERE b.book_id='$book_id'
    ";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0)
    {
        $book = mysqli_fetch_assoc($result);
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Borrow Books</title>

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/pagespec.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="content">

   

    <div class="form-box">

        <h2>Search Books</h2>

        <form method="GET">

            <input
            type="text"
            name="search"
            placeholder="Book ID, ISBN or Title"
            required>

            <button type="submit">
                Search
            </button>

        </form>

    </div>

    <?php

    if(isset($_GET['search']))
    {
        $search = $_GET['search'];

        $sql = "
        SELECT
            b.book_id,
            b.title,
            b.isbn,
            b.available_copies,
            a.author_name

        FROM books b

        INNER JOIN authors a
        ON b.author_id = a.author_id

        WHERE
            b.book_id LIKE '%$search%'
            OR b.isbn LIKE '%$search%'
            OR b.title LIKE '%$search%'

        ORDER BY b.title
        ";

        $result = mysqli_query($conn,$sql);

        if(mysqli_num_rows($result) > 0)
        {
        ?>

        <div class="table-container">

            <h2>Search Results</h2>

            <table>

                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Author</th>
                    <th>Available</th>
                    <th>Action</th>
                </tr>

                <?php

                while($row = mysqli_fetch_assoc($result))
                {
                ?>

                <tr>

                    <td><?php echo $row['book_id']; ?></td>

                    <td><?php echo $row['title']; ?></td>

                    <td><?php echo $row['isbn']; ?></td>

                    <td><?php echo $row['author_name']; ?></td>

                    <td><?php echo $row['available_copies']; ?></td>

                    <td>

                        <a
                        href="borrow_book.php?book_id=<?php echo $row['book_id']; ?>"
                        class="btn-select">

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
            echo "<h3 class='error-message'>
            No Books Found
            </h3>";
        }
    }
    ?>

   

    <?php if($book){ ?>

    <div class="form-box">

        <h2>Selected Book</h2>

        <table>

            <tr>
                <td>Book ID</td>
                <td><?php echo $book['book_id']; ?></td>
            </tr>

            <tr>
                <td>Title</td>
                <td><?php echo $book['title']; ?></td>
            </tr>

            <tr>
                <td>ISBN</td>
                <td><?php echo $book['isbn']; ?></td>
            </tr>

            <tr>
                <td>Publication Year</td>
                <td><?php echo $book['publication_year']; ?></td>
            </tr>

            <tr>
                <td>Author</td>
                <td><?php echo $book['author_name']; ?></td>
            </tr>

            <tr>
                <td>Category</td>
                <td><?php echo $book['category_name']; ?></td>
            </tr>

            <tr>
                <td>Publisher</td>
                <td><?php echo $book['publisher_name']; ?></td>
            </tr>

            <tr>
                <td>Available Copies</td>
                <td><?php echo $book['available_copies']; ?></td>
            </tr>

        </table>

        <hr>

        <h2>Borrow Details</h2>

        <form method="POST">

            <input
            type="hidden"
            name="book_id"
            value="<?php echo $book['book_id']; ?>">

            <label>Member Code</label>

            <input
            type="text"
            name="member_code"
            placeholder="M000001"
            required>

            <label>Due Date</label>

            <input
            type="date"
            name="due_date"
            required>

            <button type="submit" name="borrow_book">
    <i class="fa-solid fa-book"></i>
    Borrow Book
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
