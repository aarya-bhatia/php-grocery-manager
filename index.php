<!-- 
== Todo ==
1. pagination on home page
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<?php include "connect.php"; ?>

<?php
    $select = false;

    if($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        if(isset($_GET['select']) and htmlspecialchars($_GET['select']) === 'true')
        {
            $select = true;
        }
    }

    else if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['submit']))
        {
            if(isset($_POST['action']))
            {
                $list = join(",",$_POST['selected']);

                if($_POST['action'] === 'delete' && !empty($_POST['selected']))
                {
                    $deleteQuery = "DELETE FROM grocerytb WHERE Id IN (". $list . ")";

                    if(!mysqli_query($con, $deleteQuery))
                    {
                        echo 'Error: '.mysqli_error($con).'<br><br>';
                    }
                }
                else if($_POST['action'] == 'edit-status')
                {
                    $status = $_POST['action-value'];
                    $updateQuery = "UPDATE grocerytb SET Item_status='$status' WHERE Id IN ($list)";

                    if(!mysqli_query($con, $updateQuery))
                    {
                        echo 'Error: '.mysqli_error($con).'<br><br>';
                    }
                }
            } 

        }
    }
?>

<?php 
$results_per_page = 5;
$page_num = $_GET['page'] or 1;

if($page_num <= 0) {
    $page_num = 1;
}

$offset = $page_num * $results_per_page;

if (isset($_POST['btn'])) {
    $date = $_POST['idate'];
    $q = "SELECT * FROM grocerytb WHERE Date='$date' ORDER BY Date LIMIT $results_per_page OFFSET $offset";
    $query = mysqli_query($con, $q);
} else {
    $q = "SELECT * FROM grocerytb ORDER BY Date LIMIT $results_per_page OFFSET $offset";
    $query = mysqli_query($con, $q);
}

$num_results = $query->num_rows;
?>

<body>
    <div class="container mt-5">
        <!-- top -->
        <div class="row">
            <div class="col-lg-8">
                <h1>View Grocery List</h1>

                <?php if(!$select){ ?>
                <a class='btn btn-primary' href="add.php">Add Item</a>
                <a class='btn btn-warning' href="<?php echo $_SERVER['PHP_SELF'].'?select=true' ?>">Select Items</a>
                <?php } ?>

            </div>
            <div class="col-lg-4">

                <form method="post" action="">
                    <div class="row">
                        <div class="col-lg-8">
                            <input type="date" class="form-control" name="idate">
                        </div>
                        <div class="col-lg-4" method="post">
                            <input type="submit" class="btn btn-danger float-right" name="btn" value="filter">
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!-- Grocery Cards -->
        <form method="post" action=''>

            <div class="row mt-4">

                <?php
while ($qq = mysqli_fetch_array($query)) {

    ?>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">

                            <?php if($select){ ?>
                            <input type='checkbox' name="selected[]" value="<?=$qq['Id']?>" />
                            <?php } ?>

                            <h5 class="card-title"><?php echo $qq['Item_name']; ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $qq['Item_Quantity']; ?></h6>

                            <?php
if ($qq['Item_status'] == 0) {
        ?>
                            <p class="text-info">PENDING</p>

                            <?php
} else if ($qq['Item_status'] == 1) {
        ?>
                            <p class="text-success">BOUGHT</p>

                            <?php } else {?>
                            <p class="text-danger">NOT AVAILABLE</p>
                            <?php }?>

                            <a href="delete.php?id=<?=$qq['Id'];?>" class="card-link">Delete</a>

                            <a href="update.php?id=<?=$qq['Id'];?>" class="card-link">Update</a>

                        </div>

                    </div><br>
                </div>
                <?php

} // End loop
?>

                <!-- End Row -->
            </div>

            <?php if($select) { ?>

            <div class="row">
                <div class="col-4">
                    <select class='form-control w-50' name='action' id='action'>
                        <option value='delete'>Delete</option>
                        <option value='edit-status'>Edit Status</option>
                    </select>
                    <select class="form-control" name="action-value" id='action-value' disabled>
                        <option value="0">PENDING</option>
                        <option value="1">BOUGHT</option>
                        <option value="2">NOT AVAILABLE</option>
                    </select>

                    <script>
                    const actionValueInput = document.getElementById('action-value');

                    document.getElementById('action').addEventListener('change', function() {
                        if (this.value == 'edit-status') {
                            actionValueInput.disabled = false;
                        } else {
                            actionValueInput.disabled = true;
                        }
                    })
                    </script>
                </div>
                <div class="col-8"></div>
            </div>

            <div class="my-4">

                <input class='btn btn-primary' name='submit' type='submit' value='OK' />
                <a class='btn btn-warning' href="<?php echo $_SERVER['PHP_SELF'] ?>">Cancel</a>
            </div>

            <?php } ?>

        </form>

        <?php echo '<p>Showing '.$results_per_page .' results</p>'?>

        <nav aria-label="Page Navigation">
            <ul class="pagination">
                <li class="page-item">
                    <a class="btn page-link <?php if($page_num == 1){echo 'disabled';}?>"
                        href="<?php echo $_SERVER['PHP_SELF'].'?page='.($page_num - 1) ?>" aria-label="Previous">
                        <span aria-hidden="<?php echo ($page_num == 1 ? 'true' : 'false') ?>">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href=" <?php echo $_SERVER['PHP_SELF'].'?page=1' ?> ">1</a>
                </li>

                <li class="page-item"><a class="page-link" href=" <?php echo $_SERVER['PHP_SELF'].'?page=2' ?> ">2</a>
                </li>

                <li class="page-item"><a class="page-link" href=" <?php echo $_SERVER['PHP_SELF'].'?page=3' ?> ">3</a>
                </li>

                <a class="btn page-link <?php if($num_results < $results_per_page ){echo 'disabled';} ?>"
                    href="<?php echo $_SERVER['PHP_SELF'].'?page='.($page_num + 1) ?>" aria-label="Next">
                    <span aria-hidden="<?php
                            echo ($num_results == $results_per_page ? 'true' : 'false')
                        ?>">&raquo;</span>
                </a>
                </li>
            </ul>
        </nav>

        <h4><a href='test.php'>Add Test Items</a></h4>

        <!-- End Container -->
    </div>
</body>

</html>