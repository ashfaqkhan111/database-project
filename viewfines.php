<?php 
include 'db.php';

$sql = "
SELECT
    f.fine_id,
    f.amount,
    f.paid_status,
    m.member_code,
    m.member_name,
    b.title,
    br.borrow_date,
    br.due_date,
    br.return_date

FROM fines f

INNER JOIN borrowings br
ON f.borrow_id = br.borrow_id

INNER JOIN members m
ON br.member_id = m.member_id

INNER JOIN books b
ON br.book_id = b.book_id

ORDER BY f.fine_id DESC
";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>

<html>
    <head>
        <title>
            View Fines
        </title>
        <link rel="stylesheet" href="css/sidebar.css">
        <link rel="stylesheet" href="css/tables.css">
        <link rel="stylesheet" href="css/pagespec.css">
        <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>
    <body>
        <div class="content">
            <div class="table-container">

           
        <h1>Fine Management</h1>

        <table border="1">
            <tr>
                <th>Fine ID</th>
                    <th>Member Code</th>
                    <th>Member Name</th>
                    <th>Book Title</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Amount</th>
                    <th>Paid Status</th>
            </tr>
            <?php
            while($row = mysqli_fetch_assoc($result)){
                ?>

                <tr>
                        <td><?php echo $row['fine_id']; ?></td>

                        <td><?php echo $row['member_code']; ?></td>

                        <td><?php echo $row['member_name']; ?></td>

                        <td><?php echo $row['title']; ?></td>

                        <td><?php echo $row['borrow_date']; ?></td>

                        <td><?php echo $row['due_date']; ?></td>

                        <td><?php echo $row['return_date']; ?></td>

                        <td class="amount">
    Rp <?php echo number_format($row['amount'],0,',','.'); ?>
</td>

<td>
<?php
if($row['paid_status'] == 'Paid')
{
    echo "<span class='status-active'>Paid</span>";
}
else
{
    echo "<span class='status-inactive'>Unpaid</span>";
}
?>
</td>
              </tr>
              <?php  
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