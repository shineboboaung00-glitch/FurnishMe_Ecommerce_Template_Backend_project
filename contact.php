<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/components/connection.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    
    <?php include('components/css.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

    <?php include('components/header.php'); ?>
    <?php include('components/navbar.php'); ?>

    <!-- heading section start -->
    <section class="heading">
        <h3>our shop</h3>
        <p><a href="index.php">home</a> / <span>contact</span></p>
    </section>

    <!-- contact section start -->
    <div class="contact">
        <form id="contact_form" class="ajax-form" data-success-msg="Your message has been sent successfully!">
            <h3>get in touch</h3>

            <input type="hidden" name="module" value="contact">
            <input type="hidden" name="action_type" value="create">

            <label for="contact_name">your name</label>
            <input type="text" id="contact_name" name="name" class="box" placeholder="Enter your name">
            <span class="error-msg" id="error-name" style="color: red; font-size: 14px; display: block;"></span>

            <label for="contact_phone">your number</label>
            <input type="tel" id="contact_phone" name="phone" class="box" placeholder="Enter your phone number">
            <span class="error-msg" id="error-phone" style="color: red; font-size: 14px; display: block;"></span>

            <label for="contact_email">your email</label>
            <input type="email" id="contact_email" name="email" class="box" placeholder="Enter your email">
            <span class="error-msg" id="error-email" style="color: red; font-size: 14px; display: block;"></span>

            <label for="contact_message">your message</label>
            <textarea id="contact_message" name="message" cols="30" rows="10" class="box" placeholder="Enter your message"></textarea>
            <span class="error-msg" id="error-message" style="color: red; font-size: 14px; display: block;"></span>

            <button type="submit" class="btn">Send Message</button>
        </form>

        <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d1910.0187923655158!2d96.23546253852871!3d16.774805596002924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTbCsDQ2JzI5LjMiTiA5NsKwMTQnMTIuMyJF!5e0!3m2!1sen!2smm!4v1784382014457!5m2!1sen!2smm" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
    </div>

    <?php include('components/footer.php'); ?>
    
    <?php include('components/js.php'); ?>

</body>
</html>