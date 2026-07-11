
<?php 
include 'components/connection.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $massage = mysqli_real_escape_string($connection, $_POST['massage']);

    $data = "INSERT INTO contact (name,phone,email,massage) VALUES ('$name','$phone','$email','$massage')";

    $connection->query($data);
    header('location: contact.php');
    exit();
}


?>

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
        <p><a href="index.php">home</a> / <span>contact</span></p>
    </section>

    <!-- heading section end -->

    <!-- contact section start -->

    <div class="contact">
        <form method="POST">
            <h3>get in touch</h3>
            <span>your name</span>
            <input type="text" name="name" class="box">
            <span>your number</span>
            <input type="number" name="phone" class="box">
            <span>your email</span>
            <input type="email" name="email" class="box">
            <span>your message</span>
            <textarea cols="30" rows="10" name="massage" class="box"></textarea>
            <button type="submit" name="submit" class="btn">Send Message</button>
        </form>

        <iframe class="map"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7635.167591506357!2d96.06651810000001!3d16.896471100000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c195d9e8dad87d%3A0x27a5d8b44f026ac8!2z4YCQ4YCU4YC64YCW4YCt4YCv4YC44YCU4YCK4YC64YC44YCh4YCt4YCZ4YC64YCb4YCsIOGAoeGAleGAreGAr-GAhOGAuuGAuCjhgYEpIOGAnuGAr-GAtuGAuOGAmOGAruGAuOGAguGAreGAkOGAug!5e0!3m2!1sen!2smm!4v1727112860930!5m2!1sen!2smm"
            allowfullscreen="" loading="lazy"></iframe>
    </div>

    <!-- contact section end -->


    <?php include('components/footer.php'); ?>

    <?php include('components/js.php'); ?>

</body>

</html>