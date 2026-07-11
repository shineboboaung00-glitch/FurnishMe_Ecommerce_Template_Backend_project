<!-- footer section start  -->

<section class="footer">
    <div class="box-container">

        <div class="box">
            <h3>quick links</h3>
            <a href="index.php"> <i class="ri-arrow-right-line"></i> home </a>
            <a href="shop.php"> <i class="ri-arrow-right-line"></i> shop </a>
            <a href="about.php"> <i class="ri-arrow-right-line"></i> about </a>
            <a href="team.php"> <i class="ri-arrow-right-line"></i> team </a>
            <a href="blog.php"> <i class="ri-arrow-right-line"></i> blog </a>
            <a href="contact.php"> <i class="ri-arrow-right-line"></i> contact </a>
        </div>

        <div class="box">
            <h3>extra links</h3>
            <a href="#"> <i class="ri-arrow-right-line"></i> my order </a>
            <a href="#"> <i class="ri-arrow-right-line"></i> my wishlist </a>
            <a href="#"> <i class="ri-arrow-right-line"></i> my account </a>
            <a href="#"> <i class="ri-arrow-right-line"></i> my favorite </a>
            <a href="#"> <i class="ri-arrow-right-line"></i> terms of user </a>
        </div>

        <div class="box">
            <h3>extra links</h3>
            <a href="#"> <i class="ri-facebook-fill"></i> facebook </a>
            <a href="#"> <i class="ri-twitter-fill"></i> twitter </a>
            <a href="#"> <i class="ri-instagram-fill"></i> instagram </a>
            <a href="#"> <i class="ri-linkedin-box-fill"></i> linkedin </a>
            <a href="#"> <i class="ri-pinterest-fill"></i> pinterest </a>
        </div>


        <?php
        include 'components/connection.php';

        if (isset($_POST['submit'])) {
            $email = mysqli_real_escape_string($connection, $_POST['email']);
            $data = "INSERT INTO newsletter (email) VALUES ('$email')";

            $connection->query($data);
            header('location: contact.php');
            exit();
        }


        ?>



        <div class="box">
            <h3>newsletter</h3>
            <p>subscribe for latest updates</p>
            <form method="POST">
                <input type="email" name="email" placeholder="enter your email">
                <button type="submit" name="submit" class="btn">Send Message</button>
            </form>
        </div>

    </div>
</section>

<section class="credit">
    coded by Developer Daniel | all rights reserved!
</section>

<!-- footer section end  -->