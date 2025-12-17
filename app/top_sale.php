<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<head>
        <link rel="icon" href="images/logo1.png" >
        <title>Top Sales</title>
</head>
<div class="page-header">
  <h1>Top sale</h1>
  <div class="button-group">
    <a href="main.php" class="home-btn">🏠 Home</a>
  </div>
</div>

<div class="hero-banner">
    <div class="container">
        <div class="row">
            <div class="col-2">
                <h1>Your Best Choice !</h1>
                <p><b>Welcome!</b> Discover a wide range of tasty dishes made just for you.</p>
                <a href="product/product.php" class="btn">Explore Now →</a>
            </div>
        </div>
    </div>
</div>


<!--Best Sales-->
<div class="categories">
    <div class="small-container">
    <h2>Most Popular Product</h2>
        <div class="row">
            <div class="col-3">
                <img src="product/images_product/noodle3.png" alt="Char Kuey Teow">
                <h4>Char Kuey Teow <br> ⭐⭐⭐⭐⭐</h4>
                <p class="user-comment">"10/10 would steal a plate from my friend again! 😎🍤"</p>
            </div>

            <div class="col-3">
                <img src="product/images_product/rice3.png" alt="Korean Chicken Rice">
                <h4>Korean Chicken Rice <br> ⭐⭐⭐⭐⭐</h4>
                <p class="user-comment">"This chicken is so good I almost married it! 🍗💍"</p>
            </div>
            <div class="col-3">
                <img src="product/images_product/dessert2.png" class="product-image" alt="Cheesecake Slice">
                <h4>Cheesecake Slice <br> ⭐⭐⭐⭐⭐</h4>
                <p class="user-comment">"Calories don’t count when it tastes this good, right? 😋"</p>
            </div>
        </div>
    </div>
</div>

<!--Featured Food-->
    <div class="small-container">
        <h2>Don’t Miss This 🍽️</h2>
        <div class="row">
            <div class="col-4">
                <img src="product/images_product/rice2.png">
                <h4>Salted Egg Chicken Rice</h4>
                <p>RM 9.90</p>
            </div>

            <div class="col-4">
                <img src="product/images_product/rice8.png">
                <h4>Sweet and Sour Chicken Rice</h4>
                <p>RM 9.40</p>
            </div>

            <div class="col-4">
                <img src="product/images_product/burger8.png">
                <h4>Ramly Special Burger</h4>
                <p>RM 7.80</p>
            </div>

            <div class="col-4">
                <img src="product/images_product/chicken3.png">
                <h4>Korean Fried Chicken Soy Garlic</h4>
                <p>RM 9.90</p>
            </div>

            <div class="col-4">
                <img src="product/images_product/noodle7.png">
                <h4>Wantan Mee</h4>
                <p>RM 8.80</p>
            </div>
        </div>
        <!--Seasonal Food-->
        <h2 style="margin-top: 80px;">Seasonal Items</h2>
        <div class="row">
            <div class="col-4">
                <img src="product/images_product/dessert5.png">
                <h4>Vanilla Ice Cream</h4>
                <p>RM 4.50</p>
            </div>

            <div class="col-4">
                <img src="product/images_product/dessert3.png">
                <h4>Brownies</h4>
                <p>RM 5.90</p>
            </div>

            <div class="col-4">
                <img src="product/images_product/drink6.png">
                <h4>Strawberry Milkshake</h4>
                <p>RM 5.90</p>
            </div>
        </div>
    </div>

</body>

<style>
    /*---------------------------------- Theme Colors & Fonts ----------------------------------*/
    :root {
    --main-color: #8B0000;
    --accent-color: #FFD5D5;
    --dark-color: #1c1c1c;
    --gray-color: #777;
    --font-main: 'Poppins', sans-serif;
}

body {
    margin: 0;
    font-family: var(--font-main);
    background-color: #fff;
    color: var(--dark-color);
    line-height: 1.6;
}

/*---------------------------------- Header ----------------------------------*/
.page-header {
    background: linear-gradient(to right, #ffe5e5, #fff0f0);
    padding: 30px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h1 {
    font-size: 36px;
    color: var(--main-color);
    margin: 0;
}

.home-btn {
    background-color: var(--main-color);
    color: white;
    padding: 10px 22px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(139, 0, 0, 0.3);
}

.home-btn:hover {
    background-color: #660000;
}

/*---------------------------------- Hero Banner ----------------------------------*/
.hero-banner {
    position: relative;
    background: url('product/images_product/banner.png') no-repeat center center/cover;
    padding: 100px 20px;
    color: white;
    text-align: left;
    z-index: 1;
}

.hero-banner::before {
    content: "";
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 228, 230, 0.3);
    z-index: 0;
}

.hero-banner .container {
    position: relative;
    z-index: 2;
}

.hero-banner h1 {
    font-size: 48px;
    margin-bottom: 20px;
    color: #fff;
    text-shadow: 0 3px 8px rgba(0, 0, 0, 0.5);
}

.hero-banner p {
    font-size: 18px;
    color: #fdfdfd;
    margin-bottom: 30px;
    text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
}

.hero-banner .btn {
    background: linear-gradient(to right, #ffffff, #ffe5e5); /* 白到淡粉渐变 */
    color: var(--main-color);
    padding: 12px 28px;
    font-weight: 600;
    border-radius: 30px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.hero-banner .btn:hover {
    background: var(--accent-color);
    color: white;
}


/*---------------------------------- Section Titles ----------------------------------*/
.small-container h2 {
    color: var(--main-color);
    font-size: 32px;
    text-align: center;
    margin: 60px 0 30px;
}

/*---------------------------------- Product Grid ----------------------------------*/
.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 40px;
    padding: 0 15px;
}

.col-3, .col-4 {
    background-color: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    padding: 20px;
    flex: 0 0 22%;
    max-width: 22%;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.col-3:hover, .col-4:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

/*---------------------------------- Images ----------------------------------*/
.col-3 img, .col-4 img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 15px;
}
.product-image {
    width: 100%;
    height: 200px;             
    object-fit: cover;        
    object-position: center 80%;    
    border-radius: 12px;        
}
/*---------------------------------- Text ----------------------------------*/
.col-3 h4, .col-4 h4 {
    font-size: 20px;
    color: var(--dark-color);
    margin-bottom: 8px;
}

.col-3 p, .col-4 p {
    color: var(--main-color);
    font-weight: 600;
    font-size: 16px;
}

/*---------------------------------- Rating ----------------------------------*/
.user-comment {
    font-style: italic;
    font-size: 0.9rem;
    color: #555;
    margin-top: 5px;
}


/*---------------------------------- Responsive Design ----------------------------------*/
@media (max-width: 992px) {
    .col-3, .col-4 {
        flex: 0 0 30%;
        max-width: 30%;
    }
}

@media (max-width: 768px) {
    .col-3, .col-4 {
        flex: 0 0 45%;
        max-width: 45%;
    }

    .hero-banner h1 {
        font-size: 32px;
    }
}

@media (max-width: 480px) {
    .col-3, .col-4 {
        flex: 0 0 90%;
        max-width: 90%;
    }

    .hero-banner {
        padding: 60px 15px;
    }
}
</style>