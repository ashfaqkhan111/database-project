<?php

include 'db.php';

if(isset($_GET['deactivate']))
{
    $id = $_GET['deactivate'];

    mysqli_query(
        $conn,
        "UPDATE books
         SET status='Inactive'
         WHERE book_id='$id'"
    );

    echo "<h3>Book Deactivated Successfully</h3>";
}

if(isset($_GET['activate']))
{
    $id = $_GET['activate'];

    mysqli_query(
        $conn,
        "UPDATE books
         SET status='Active'
         WHERE book_id='$id'"
    );

    echo "<h3>Book Activated Successfully</h3>";
}

if(isset($_GET['delete']))
{
    $id = $_GET['delete'];

    $check =
    mysqli_query(
        $conn,
        "SELECT *
         FROM borrowings
         WHERE book_id='$id'"
    );

    if(mysqli_num_rows($check) > 0)
    {
        echo "<h3>Cannot delete book. Borrowing records exist.</h3>";
    }
    else
    {
        mysqli_query(
            $conn,
            "DELETE FROM books
             WHERE book_id='$id'"
        );

        echo "<h3 style='color:green'>
        Book Deleted Successfully
        </h3>";
    }
}

if(isset($_POST['update_book']))
{
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $publication_year = $_POST['publication_year'];
    $available_copies = $_POST['available_copies'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher_id = $_POST['publisher_id'];
    $status = $_POST['status'];

    $sql = "
    UPDATE books
    SET
        title='$title',
        isbn='$isbn',
        publication_year='$publication_year',
        available_copies='$available_copies',
        author_id='$author_id',
        category_id='$category_id',
        publisher_id='$publisher_id',
        status='$status'
    WHERE book_id='$book_id'
    ";

    if(mysqli_query($conn,$sql))
    {
        header("Location: edit_book.php");
        exit();
    }
    else
    {
        die(
            'Update Error: '
            . mysqli_error($conn)
        );
    }
}

?>
<html>
    <head>
        <title>edit books</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/pagespec.css">
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>
   
    <body>





<?php
if(!isset($_GET['id']))
{
?>
<div class="content">
    <div class="table-container">

<h2>Search Books</h2>

<form class="search-form" method="GET">
    <input
    type="text"
    name="search"
    placeholder="Search by Title, ISBN, Year or Author"
    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

    <button type="submit">
        Search
    </button>
</form>

<br>

<?php

$search = isset($_GET['search']) ? $_GET['search'] : "";

$sql = "
SELECT
    b.book_id,
    b.title,
    b.isbn,
    b.publication_year,
    b.status,
    a.author_name
FROM books b
LEFT JOIN authors a
ON b.author_id = a.author_id
";

if($search != "")
{
    $sql .= "
    WHERE
        b.title LIKE '%$search%'
        OR b.isbn LIKE '%$search%'
        OR b.publication_year LIKE '%$search%'
        OR a.author_name LIKE '%$search%'
    ";
}

$result = mysqli_query($conn,$sql);

echo "
<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>ISBN</th>
    <th>Year</th>
    <th>Author</th>
    <th>Status</th>
    <th>Edit</th>
    <th>Deactivate</th>
    <th>Activate</th>
    <th>Delete</th>
</tr>
";

while($row = mysqli_fetch_assoc($result))
{
    echo "
    <tr>
    <td>".$row['book_id']."</td>
    <td>".$row['title']."</td>
    <td>".$row['isbn']."</td>
    <td>".$row['publication_year']."</td>
    <td>".$row['author_name']."</td>
    <td>".$row['status']."</td>

    <td><a href='edit_book.php?id=".$row['book_id']."'>Edit</a></td>

    <td><a href='edit_book.php?deactivate=".$row['book_id']."'>Deactivate</a></td>

    <td><a href='edit_book.php?activate=".$row['book_id']."'>Activate</a></td>

    <td><a href='edit_book.php?delete=".$row['book_id']."'>Delete</a></td>
    </tr>
    ";
}

echo "</table>";
?>

</div>
</div>

<?php
}

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $book_query = mysqli_query(
        $conn,
        "SELECT * FROM books WHERE book_id='$id'"
    );

    $book = mysqli_fetch_assoc($book_query);

    if($book)
    {
?>

<div class="content">
<div class="form-box">
<h2>Edit Book</h2>

<form method="POST">

<input type="hidden" name="book_id"
value="<?php echo $book['book_id']; ?>">

<label>Title</label>
<input type="text" name="title"
value="<?php echo $book['title']; ?>" required>

<label>ISBN</label>
<input type="text" name="isbn"
value="<?php echo $book['isbn']; ?>" required>

<label>Publication Year</label>
<input type="number" name="publication_year"
value="<?php echo $book['publication_year']; ?>" required>

<label>Available Copies</label>
<input type="number" name="available_copies"
value="<?php echo $book['available_copies']; ?>" required>

<label>Author</label>
<select name="author_id">
<?php
$authors = mysqli_query($conn,"SELECT * FROM authors");
while($author = mysqli_fetch_assoc($authors))
{
?>
<option value="<?php echo $author['author_id']; ?>"
<?php if($author['author_id']==$book['author_id']) echo "selected"; ?>>
<?php echo $author['author_name']; ?>
</option>
<?php } ?>
</select>

<label>Category</label>
<select name="category_id">
<?php
$categories = mysqli_query($conn,"SELECT * FROM categories");
while($category = mysqli_fetch_assoc($categories))
{
?>
<option value="<?php echo $category['category_id']; ?>"
<?php if($category['category_id']==$book['category_id']) echo "selected"; ?>>
<?php echo $category['category_name']; ?>
</option>
<?php } ?>
</select>

<label>Publisher</label>
<select name="publisher_id">
<?php
$publishers = mysqli_query($conn,"SELECT * FROM publishers");
while($publisher = mysqli_fetch_assoc($publishers))
{
?>
<option value="<?php echo $publisher['publisher_id']; ?>"
<?php if($publisher['publisher_id']==$book['publisher_id']) echo "selected"; ?>>
<?php echo $publisher['publisher_name']; ?>
</option>
<?php } ?>
</select>

<label>Status</label>
<select name="status">
<option value="Active" <?php if($book['status']=="Active") echo "selected"; ?>>Active</option>
<option value="Inactive" <?php if($book['status']=="Inactive") echo "selected"; ?>>Inactive</option>
</select>

<button type="submit" name="update_book">
    <i class="fa-solid fa-pen-to-square"></i>
    Update Book
</button>


</form>


<?php
    }
}
?>

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

