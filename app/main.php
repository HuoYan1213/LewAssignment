<?php
include 'base.php';

// ----------------------------------------------------------------------------

// Authenticated users
auth('admin','member');

$_title = 'TARUMT Cafeteria Food Ordering System';
include 'head2.php';
?>

<img src="images/poster.png" alt="poster of TARUMT cafeteria" style="width:100%;">
<hr/><p style="font-weight:bold;font-size:30px;text-align:center;font-family:'Serif',Georgia"> Just one fingertip we "settle" for you !</p><hr/>

<h3>Why choose us ?</h3>

  <div class="stats-container">
    <div class="stat-card">
      <h2>100+</h2>
      <p>Restaurants registered</p><br/><br/>
    </div>
    <div class="stat-card">
      <h2>7K++</h2>
      <p>Ratings 4.0+</p><br/><br/>
    </div>
    <div class="stat-card">
      <h2>10K+</h2>
      <p>Orders Processed</p><br/><br/>
    </div>
  </div>
  <p style="text-align: justify; margin: 0 auto; width: 60%; font-size: 20px;">
  <br/><br/><b><i>TARUMT Cafeteria</i></b> skip the queues. Order ahead and enjoy a smoother cafeteria experience at TAR UMT.
</p>


  <div class="eligible">
    <P> Where to find us?</p>
    <p>Our Location - Kampar, Perak</p>
  <iframe 
    class="map-container"
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15928.343574901714!2d101.12447784784442!3d4.31420306492526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cb14d138ea3709%3A0x9b570ec6d3e25ec9!2sKampar%2C%20Perak!5e0!3m2!1sen!2smy!4v1714027110740!5m2!1sen!2smy" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
  </div>

 <div class="video">  <video src="images/add.mp4" autoplay muted loop playsinline>></video>
 <div class="slogan">Your cravings, just a tap away.
  <br/><br/><a href="product/product.php">
        <button>Get Started</button>
      </a></div></div>

      <div class="know">
  <div class="know-content">
    <h1>
    💡Get to know us more!
    </h1>
    <a href="about_us/us.php" class="know-btn">Learn More</a>
  </div>
  <div class="know-image">
    <img src="images/about-preview.jpg" alt="About Us Preview" />
  </div>
</div>

<?php
include 'footer.php';