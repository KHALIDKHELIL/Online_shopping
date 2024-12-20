<?php
@include 'db_connc.php';
session_start();  // Start the session

// Check if user is logged in
$isLoggedIn = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : false;


$sql4 = "SELECT * FROM product JOIN stock on product.Id=stock.itemID limit 6";
$selectc = mysqli_query($conn, $sql4);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>ብራንድ</title>
  <link rel="stylesheet" href="Home.css">
  <link rel="stylesheet" href="cart.css">
  <style>
    .slider-container {
      width: 100%;
      overflow-x: scroll;
      /* Enable horizontal scrolling */
      display: flex;
      gap: 20px;
      padding: 10px;
      scroll-snap-type: x mandatory;
      /* Enable snap scrolling */
      scroll-behavior: smooth;
      /* Smooth scrolling */
    }

    .slider-container::-webkit-scrollbar {
      height: 8px;
      /* Visible horizontal scrollbar */
    }

    .slider-container::-webkit-scrollbar-thumb {
      background: #888;
      /* Thumb color */
      border-radius: 4px;
      /* Rounded edges */
    }

    .slider-container::-webkit-scrollbar-thumb:hover {
      background: #555;
      /* Thumb color on hover */
    }

    .slider {
      display: flex;
      flex-wrap: nowrap;
      /* Prevent wrapping */
      gap: 20px;
    }

    .card {
      flex: 0 0 calc(33.333% - 20px);
      /* Each card takes 1/3 of the width */
      max-width: calc(33.333% - 20px);
      background-color: #f4f4f4;
      /* border-radius: 8px; */
      /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
      text-align: center;
      padding: 10px;
      scroll-snap-align: start;
      /* Snap card to start */
      box-shadow: 15px 10px 15px 10px rgba(1, 5, 8, 0.4),
        0 4px 6px -4px rgba(1, 7, 12, 0.4);
      border-radius: 10px;
      background-color: #6b6ecc;
      background: #fdfcfc
        /*linear-gradient(45deg, #04051dea 0%, #2b566e 100%)*/
      ;
    }

    .card img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
    }
  </style>
</head>

<body>
  <header>
    <div class="header-container">
      <a href="/My Market.php" class="logo-link">
        <img src="image/logo.jpg" alt="logo" class="logo">
      </a>
      <nav class="nav-bar">
        <?php if (!$isLoggedIn):
        ?>
          <a href="login/register.php" class="nav-link">Register</a>
        <?php endif; ?>

        <a href="Productpage.php" class="nav-link">Products</a>
        <a href="ABOUTUS.PHP" class="nav-link">More</a>
      </nav>
    </div>
  </header>




  <div class="main-photo">
    <div class="header2">
      <h1 id="Welcome"> ብራንድ <i class="fa fa-shopping-cart" aria-hidden="true"></i></h1>
    </div>
    <p>Anything you want</p> <br><br><br>
    <a target="_blank" href="ABOUTUS.PHP" class="btn">Learn more</a>

  </div>




  <main class="slider-container">

    <?php
    if (mysqli_num_rows($selectc) > 0) {
      while ($rowp = mysqli_fetch_assoc($selectc)) {
    ?>
        <div class="card">
          <div class="content">
            <div class="title">
              <a href="Productpage.php">
                <img width="70px;" src="php admin crud/uploaded_img/<?php echo $rowp['productImage'] ?>" alt="">
              </a>
            </div>
            <div class="price">$ <?php echo $rowp['sellPrice'] ?></div>
          </div>
          <a href="../Final_Project/Productpage.php">
            <button>View</button>
          </a>
        </div>
    <?php
      }
    } else {
      echo "<p>No products available.</p>";
    }
    ?>

  </main>

  <footer>
    <div class="footer-wrap">

      <div class="widgetFooter">
        <h4 class="uppercase">useful links</h4>
        <ul id="footerUsefulLink">
          <li title="About US">
            <span class="usefulLinksIcons">
              <i class="far fa-id-card"></i>
            </span> <!-- HERE ADD THE USEFUL LINKS WHEN FINISHING, DON'T YOU FORGET KHALID :() -->
            <a href="ABOUTUS.PHP">&nbsp;About us</a>
          </li>
          <li title="Our Team">
            <span class="usefulLinksIcons">
              <i class="far fa-handshake"></i>
            </span>
            <a href="ABOUTUS.PHP">&nbsp;Our team</a>
          </li>

          <li title="Contact Us">
            <span class="usefulLinksIcons">
              <i class="far fa-envelope"></i>
            </span>
            <a target="_blank" href="mailto:khalidkhelil19@gmail.com">&nbsp;Contact us</a>
          </li>
        </ul>
      </div>

      <!-- THE LOGO IN BETWEEN -->
      <div class="widgetFooter" id="footerLogo">
        <img style="border-radius: 50%;" src="image/logo.jpg" alt="Logo">
      </div>


      <div class="widgetFooter">
        <h4 class="uppercase">Social media links</h4>
        <ul id="footerMediaLinks">
          <li class="media1" title="Facebook">
            <span class="mediaLinksIcons fb">
              <i class="fab fa-facebook-square"></i>
            </span>
            <a class="fb" target="_blank" href="https://www.facebook.com/khalidkhelil">&nbsp;facebook</a>
          </li>
          <li class="media2" target="_blank" title="Twitter">
            <span class="mediaLinksIcons twit">
              <i class="fab fa-twitter-square"></i>
            </span>
            <a class="twit" target="_blank" href="https://www.Twitter.com/khalidkhelil">&nbsp;Twitter</a>
          </li>
          <li class="media3" title="Instagram">
            <span class="mediaLinksIcons insta">
              <i class="fab fa-instagram"></i>
            </span>
            <a class="insta" target="_blank" href="https://www.instagram.com/khalid.khelil">&nbsp;instagram</a>
          </li>
          <li class="media4" title="Github">
            <span class="mediaLinksIcons git">
              <i class="fab fa-github-alt"></i>
            </span>
            <a target="_blank" class="git" href="https://www.github.com/khalidkhelil">&nbsp;Github</a>
          </li>
        </ul>
      </div>


    </div>


    <div class="footerCopy">
      <div class="inb">
        <p style="padding:5px; line-height: 10px; word-spacing: 1.5px; letter-spacing: 2px; ">Copyrights<sup>&copy;</sup> 2024. All Rights Reserved. <br> Developed with <i class="fas fa-heart" style="color: rgb(222, 27, 27);"></i> by <a target="_blank" href="ABOUTUS.PHP" style="text-decoration: none; color: rgb(144, 144, 229);">OUR TEAM</a></p>
      </div>
    </div>
  </footer>
</body>

</html>