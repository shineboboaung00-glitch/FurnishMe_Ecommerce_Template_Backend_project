<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <?php include('components/css.php'); ?>

</head>

<body>

    <?php
    // session_start();

    // if (!isset($_SESSION['username'])) {
    //     header('Location: login.php');
    //     exit();
    // }
    ?>

    <?php include('components/connection.php'); ?>

    <?php include('components/header.php'); ?>


    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->

    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>blog</span></p>
    </section>

    <!-- heading section end -->

    <!-- blog section start -->

    <section class="blog">
        <h1 class="title"> <span>our blogs</span> <a href="blog_create.php" class="btn add_new_service">Add New Blog</a> </h1>

        <div class="box-container">

            <?php

            $data = "SELECT * FROM blog ORDER BY id ASC";
            $read_data = $connection->query($data);
            $counter = 1;
            while (list($id, $title, $description, $date, $creator, $image) = mysqli_fetch_array($read_data)) {

            ?>

                <div class="box">
                    <div class="image">
                        <img src="static/<?php echo $image ?>">
                    </div>
                    <div class="content">
                        <h3><?php echo $title ?></h3>
                        <p><?php echo $description ?></p>
                        <a href="blog_update.php?id=<?php echo $id; ?>" class="btn">Update</a>
                        <a href="blog_delete.php?id=<?php echo $id; ?>" class="btn">Delete</a>
                        <div class="icons">
                            <i class="fas fa-calendar"> <?php echo $date ?></i>
                            <i class="fas fa-user"> by <?php echo $creator ?></i>
                        </div>
                    </div>
                </div>

            <?php
                $counter++;
            } ?>



        </div>
    </section>

    <!-- blog section end -->



    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>