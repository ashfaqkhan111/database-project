
<?php
include 'db.php';

if(isset($_POST['add_author']))
{
    $author_name = $_POST['author_name'];
    $country = $_POST['country'];

    $sql = "
    INSERT INTO authors
    (
        author_name,
        country
    )
    VALUES
    (
        '$author_name',
        '$country'
    )
    ";

    if(mysqli_query($conn,$sql))
{
    echo "
    <div class='success-message'>
        Author Added Successfully
    </div>
    ";
}
else
{
    die("Error: " . mysqli_error($conn));
}
}

if(isset($_POST['add_publisher']))
{
    $publisher_name = $_POST['publisher_name'];
    $address = $_POST['address'];

    $sql = "
    INSERT INTO publishers
    (
        publisher_name,
        address
    )
    VALUES
    (
        '$publisher_name',
        '$address'
    )
    ";

    if(mysqli_query($conn,$sql))
{
    echo "
    <div class='success-message'>
        Publisher Added Successfully
    </div>
    ";
}
else
{
    die("Error: " . mysqli_error($conn));
}
}

if(isset($_POST['add_category']))
{
    $category_name = $_POST['category_name'];

    $sql = "
    INSERT INTO categories
    (
        category_name
    )
    VALUES
    (
        '$category_name'
    )
    ";

   if(mysqli_query($conn,$sql))
{
    echo "
    <div class='success-message'>
        Category Added Successfully
    </div>
    ";
}
else
{
    die("Error: " . mysqli_error($conn));
}
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Library Data</title>

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

        <h1>Add Author</h1>

        <form method="POST">

            <label>Author Name</label>

            <input
            type="text"
            name="author_name"
            placeholder="John"
            required>

            <label>Country</label>

            <input
            type="text"
            name="country"
            placeholder="USA"
            required>

           <button type="submit" name="add_author">
    <i class="fa-solid fa-plus"></i>
    Add Author
</button>

        </form>

    </div>

    <div class="form-box">

        <h1>Add Publisher</h1>

        <form method="POST">

            <label>Publisher Name</label>

            <input
            type="text"
            name="publisher_name"
            placeholder="Pearson"
            required>

            <label>Address</label>

            <input
            type="text"
            name="address"
            placeholder="Publisher Address"
            required>

          <button type="submit" name="add_publisher">
    <i class="fa-solid fa-plus"></i>
    Add Publisher
</button>

        </form>

    </div>

    <div class="form-box">

        <h1>Add Category</h1>

        <form method="POST">

            <label>Category Name</label>

            <input
            type="text"
            name="category_name"
            placeholder="Technical"
            required>

           <button type="submit" name="add_category">
    <i class="fa-solid fa-plus"></i>
    Add Category
</button>

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

