<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About page</title>

    <?php include('components/css.php'); ?>

</head>

<body>

    <?php include('components/connection.php'); ?>

    <?php include('components/header.php'); ?>

    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->

    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>about</span></p>
    </section>

    <!-- heading section end -->



    <!-- about section start -->

    <section class="about">

        <div class="image">
            <img src="static/about-img.jpg" alt="">
        </div>

        <div class="content">
            <span>welcome to our shop</span>
            <h3>we make your home more astonishing</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid fugit labore facere non autem soluta tempora natus, sequi obcaecati esse officia aspernatur impedit dignissimos ut porro praesentium similique numquam magnam.</p>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Numquam sequi illo cum expedita, eius laboriosam? Blanditiis quisquam, iure nisi, ex amet odit obcaecati eaque voluptatibus porro repudiandae sit libero debitis!</p>
            <a href="#" class="btn">read more</a>
        </div>

    </section>

    <!-- about section end -->


    <!-- services section start -->

    <section class="services">

        <h1 class="title"> <span>our services</span> <a href="service_create.php" class="btn add_new_service">Add New Service</a></h1>

        <div class="box-container">

            <?php

            $data = "SELECT * FROM service ORDER BY id ASC";
            $read_data = $connection->query($data);
            $counter = 1;
            while (list($id, $title, $description, $image) = mysqli_fetch_array($read_data)) {

            ?>

                <div class="box">
                    <img src="static/<?php echo $image ?>"style="width: 100px; height: 100px; object-fit: contain;">
                    <h3><?php echo $title ?></h3>
                    <p><?php echo $description ?></p>
                    <a href="service_update.php?id=<?php echo $id; ?>" class="btn">Update</a>
                    <a href="service_delete.php?id=<?php echo $id; ?>" class="btn">Delete</a>
                </div>

            <?php
                $counter++;
            } ?>



        </div>

    </section>

    <!-- services section end -->






    <?php include('components/footer.php'); ?>
    <?php include('components/js.php'); ?>

</body>

</html>