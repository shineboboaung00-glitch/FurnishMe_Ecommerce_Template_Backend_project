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


    <?php include('components/header.php'); ?>


    <?php include('components/navbar.php'); ?>



    <!-- heading section start -->

    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>team</span></p>
    </section>

    <!-- heading section end -->

    <!-- team section start -->

    <section class="team">

        <h1 class="title"> <span>our team</span> <a href="team_create.php" class="btn add_new_service">Add New Team Member</a></h1>

        <div class="box-container">

                <div class="box">
                    <div class="icons">
                        <a href="team_update.php?id=" class="btn">Update</a>
                        <a href="team_delete.php?id=" class="btn">Delete</a>
                    </div>
                    <div class="image">
                        <img src="static/">
                    </div>
                    <div class="user">
                        <h3></h3>
                        <span></span>
                    </div>
                </div>


        </div>
    </section>

    <!-- team section end -->







    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>