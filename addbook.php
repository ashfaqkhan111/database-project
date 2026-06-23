<?php
include 'db.php';
if(isset($_POST['add_book'])){
    $title = $_POST['title'];
    $isbn = $_POST['isbn'];
    $publication_year = $_POST['publication_year'];
    $avalible_copies = $_POST['available_copies'];
    $author_id = $_POST['author_id'];
    $category_id = $_POST['category_id'];
    $publisher_id = $_POST['publisher_id'];

    $sql = "INSERT INTO books(title,isbn,publication_year,available_copies, author_id,category_id,publisher_id)
    VALUES ('$title','$isbn','$publication_year','$avalible_copies','$author_id','$category_id','$publisher_id')";

    if(mysqli_query($conn, $sql))
{
    echo "<div class='success-message'>Book Added Successfully</div>";
}
else
{
    echo "<div class='error-message'>Error: "
         . mysqli_error($conn)
         . "</div>";
}

}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Book</title>
        <link rel="stylesheet" href="css/global.css">
        <link rel="stylesheet" href="css/tables.css">
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>
    <body>
        
        <div class="form-box">
            <h1>Add Book</h1>
        <form method="POST">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" placeholder="programming" required>

            <label for="isbn">ISBN</label>
            <input type="text" name="isbn" id="isbn" placeholder="5345456rt65" required>

            <label for="publication_year"> Publication Year</label>
            <input type="number" name="publication_year" id="publication_year" placeholder="2001" min="1850" max="2100" required>

            <label for="available_copies">Available Copies</label>
            <input type="number" name="available_copies" id="available_copies" required>

            <label for="author">Author Name</label>
            <div class="select-add-book"></div>
            <select name="author_id" id="">
                <?php
                $sql = "SELECT * FROM authors";
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <option value="<?php echo $row['author_id']; ?>">
                        <?php echo $row['author_name']; ?>
                    </option>
                    
                    <?php
                    
                }
                ?>
            </select>

            <label for="category">Category</label>
            <select name="category_id">

            <?php
            $sql = "SELECT * FROM categories";
            $result = mysqli_query($conn,$sql);

            while($row = mysqli_fetch_assoc($result)){
                ?>
                <option value="<?php echo $row['category_id']; ?>">
                    <?php echo $row['category_name']; ?>
                </option>
                <?php
            }
            ?>

            </select>
            <label for="publisher_id">Publisher</label> 
            <select name="publisher_id">

                <?php
                $sql = "SELECT * FROM publishers";
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_assoc($result)){
                    ?>

                    <option value="<?php echo $row['publisher_id'];?>">
                <?php echo $row['publisher_name']; ?>        
                </option>
                <?php
                }
                ?>

            </select>
            <button type="submit" name="add_book">
    <i class="fa-solid fa-plus"></i>
    Add Book
</button>
        </form>
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
