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

    <?php include('components/connection.php'); ?>

    <?php include('components/header.php'); ?>

    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->

    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>shop</span></p>
    </section>

    <!-- heading section end -->


    <!-- category section start -->

    <section class="category">
        <h1 class="title"> <span>our categories</span> <a href="category_create.php" class="btn title-btn">Add new category</a> </h1>

        <div class="box-container">

            <?php

            $data = "SELECT * FROM category ORDER BY id ASC";
            $read_data = $connection->query($data);
            $counter = 1;
            while (list($id, $name, $image) = mysqli_fetch_array($read_data)) {

            ?>

                <div class="box">
                    <a href="#">
                        <img src="static/<?php echo $image ?>">
                        <h3><?php echo $name ?></h3>
                    </a>
                    <a href="category_update.php?id=<?php echo $id; ?>" class="btn">Update</a>
                    <a href="category_delete.php?id=<?php echo $id; ?>" class="btn">Delete</a>
                </div>

            <?php
                $counter++;
            } ?>


        </div>
    </section>

    <!-- category section end -->


    <!-- products section start -->

    <section class="products">
        <h1 class="title"> <span>our products</span> <button onclick="window.location.href='product_create.php'" class="btn title-btn">Add new product</button></h1>

        <div class="box-container">

            <?php

            $data = "SELECT * FROM product ORDER BY id ASC";
            $read_data = $connection->query($data);
            $counter = 1;
            while (list($id, $name, $price, $quantity, $image) = mysqli_fetch_array($read_data)) {

            ?>

                <div class="box">
                    <div class="icons">
                        <a href="product_update.php?id=<?php echo $id; ?>" class="btn">Update</a>
                        <a href="product_delete.php?id=<?php echo $id; ?>" class="btn">Delete</a>
                    </div>
                    <div class="image">
                        <img src="static/<?php echo $image ?>">
                    </div>
                    <div class="content">
                        <div class="price"><?php echo $price ?></div>
                        <h3><?php echo $name ?></h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span><?php echo $quantity ?></span>
                        </div>
                    </div>
                </div>

            <?php
                $counter++;
            } ?>




        </div>
    </section>

    <!-- products section end -->

    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>