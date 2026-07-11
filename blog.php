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
        <h1 class="title"> <span>our blogs</span> <a href="#">view all >></a> </h1>

        <div class="box-container">

            <div class="box">
                <div class="image">
                    <img src="image/blog-1.jpg" alt="">
                </div>
                <div class="content">
                    <h3>blog title goes here ...</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed minus, iusto quasi laboriosam
                        voluptatibus at esse maxime quod quae mollitia molestiae magnam aperiam aspernatur eius
                        obcaecati enim eveniet quis fugit!</p>
                    <a href="#" class="btn">read more</a>
                    <div class="icons">
                        <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2023</a>
                        <a href="#"> <i class="fas fa-user"></i> by admin</a>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="image/blog-2.jpg" alt="">
                </div>
                <div class="content">
                    <h3>blog title goes here ...</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed minus, iusto quasi laboriosam
                        voluptatibus at esse maxime quod quae mollitia molestiae magnam aperiam aspernatur eius
                        obcaecati enim eveniet quis fugit!</p>
                    <a href="#" class="btn">read more</a>
                    <div class="icons">
                        <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2023</a>
                        <a href="#"> <i class="fas fa-user"></i> by admin</a>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="image/blog-3.jpg" alt="">
                </div>
                <div class="content">
                    <h3>blog title goes here ...</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed minus, iusto quasi laboriosam
                        voluptatibus at esse maxime quod quae mollitia molestiae magnam aperiam aspernatur eius
                        obcaecati enim eveniet quis fugit!</p>
                    <a href="#" class="btn">read more</a>
                    <div class="icons">
                        <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2023</a>
                        <a href="#"> <i class="fas fa-user"></i> by admin</a>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="image/blog-4.jpg" alt="">
                </div>
                <div class="content">
                    <h3>blog title goes here ...</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed minus, iusto quasi laboriosam
                        voluptatibus at esse maxime quod quae mollitia molestiae magnam aperiam aspernatur eius
                        obcaecati enim eveniet quis fugit!</p>
                    <a href="#" class="btn">read more</a>
                    <div class="icons">
                        <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2023</a>
                        <a href="#"> <i class="fas fa-user"></i> by admin</a>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="image/blog-5.jpg" alt="">
                </div>
                <div class="content">
                    <h3>blog title goes here ...</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed minus, iusto quasi laboriosam
                        voluptatibus at esse maxime quod quae mollitia molestiae magnam aperiam aspernatur eius
                        obcaecati enim eveniet quis fugit!</p>
                    <a href="#" class="btn">read more</a>
                    <div class="icons">
                        <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2023</a>
                        <a href="#"> <i class="fas fa-user"></i> by admin</a>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="image/blog-6.jpg" alt="">
                </div>
                <div class="content">
                    <h3>blog title goes here ...</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed minus, iusto quasi laboriosam
                        voluptatibus at esse maxime quod quae mollitia molestiae magnam aperiam aspernatur eius
                        obcaecati enim eveniet quis fugit!</p>
                    <a href="#" class="btn">read more</a>
                    <div class="icons">
                        <a href="#"> <i class="fas fa-calendar"></i> 21st may, 2023</a>
                        <a href="#"> <i class="fas fa-user"></i> by admin</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- blog section end -->



    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>