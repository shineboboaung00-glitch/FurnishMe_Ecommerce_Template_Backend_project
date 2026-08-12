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
        <p><a href="index.php">home</a> / <span>contact</span></p>
    </section>

    <!-- heading section end -->

    <!-- contact section start -->

    <div class="contact">
        <form id="contact_form">
            <h3>get in touch</h3>

            <input type="hidden" name="module" value="contact">
            <input type="hidden" name="action_type" value="create">

            <span>your name</span>
            <input type="text" class="box">

            <span>your number</span>
            <input type="number" class="box">

            <span>your email</span>
            <input type="email" class="box">

            <span>your message</span>
            <textarea cols="30" rows="10" class="box"></textarea>

            <button type="submit" class="btn">Send Message</button>
        </form>

        <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d1910.0187923655158!2d96.23546253852871!3d16.774805596002924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTbCsDQ2JzI5LjMiTiA5NsKwMTQnMTIuMyJF!5e0!3m2!1sen!2smm!4v1784382014457!5m2!1sen!2smm" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>

    </div>

    <!-- contact section end -->


    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>