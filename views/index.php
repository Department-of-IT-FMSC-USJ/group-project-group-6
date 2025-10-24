<?php
$pageTitle = 'Home - EcoCollect Lanka';
include __DIR__ . '/includes/header.php';
?>

<div class="heading">
    <h1>
        Transform Sri Lanka's
        <span class="gradient">Plastic Waste</span> Challenge
    </h1>
    <p>
        Join the digital revolution in plastic waste management. Connect with
        certified collection partners, earn rewards for recycling and
        contribute to a cleaner, greener Sri Lanka.
    </p>
    <div class="get-started-container" style="margin: 20px 0;">
        <a href="<?php echo $is_logged_in ? '/views/dashboard.php' : '/views/register.php'; ?>" class="get-started-link">
            <button class="get-started-button">Get Started</button>
        </a>
    </div>
</div>

<div class="grid_container">
    <div class="box">
        <div>♻️</div>
        <div><h4>Smart Collection</h4></div>
        <div class="intro">Efficient Route Optimization</div>
    </div>

    <div class="box">
        <div>🏆</div>
        <div><h4>Reward System</h4></div>
        <div class="intro">Points for every recycling action</div>
    </div>

    <div class="box">
        <div>👥</div>
        <div><h4>Community</h4></div>
        <div class="intro">Connect with Eco warriors</div>
    </div>

    <div class="box">
        <div>❤️</div>
        <div><h4>Impact</h4></div>
        <div class="intro">Measurable environmental change</div>
    </div>
</div>

<div class="heading">
    <h1>Why Choose EcoCollect?</h1>
    <p>
        Our platform makes plastic recycling simple, rewarding and impactful for
        all Sri Lankans.
    </p>
</div>

<div id="specs">
    <div class="box">
        <div>♻️</div>
        <div><h4>Easy Recycling</h4></div>
        <div class="intro">Simple pickup request from your doorstep</div>
    </div>

    <div class="box">
        <div>🏆</div>
        <div><h4>Earn Points</h4></div>
        <div class="intro">Get Rewards for Every Kilogram Recycled</div>
    </div>

    <div class="box">
        <div>🚚</div>
        <div><h4>Reliable Collection</h4></div>
        <div class="intro">Verified Collection Partners Across Sri Lanka</div>
    </div>

    <div class="box">
        <div>🌎</div>
        <div><h4>Environmental Impact</h4></div>
        <div class="intro">Track Your Contribution to a Cleaner Sri Lanka</div>
    </div>
</div>

<div class="heading">
    <h1>Our Services</h1>
    <p>Comprehensive waste management solutions for everyone</p>
</div>

<div class="grid_container">
    <div class="box">
        <div>🏠</div>
        <div><h4>For Households</h4></div>
        <div class="intro">Schedule pickups, earn points, track your impact</div>
    </div>

    <div class="box">
        <div>🏢</div>
        <div><h4>For Businesses</h4></div>
        <div class="intro">Bulk collection services and corporate rewards</div>
    </div>

    <div class="box">
        <div>🚛</div>
        <div><h4>Collection Centers</h4></div>
        <div class="intro">Register your center and manage collections</div>
    </div>

    <div class="box">
        <div>🎁</div>
        <div><h4>Affiliated Brands (Coming Soon)</h4></div>
        <div class="intro">Partner with us to offer exclusive discounts</div>
    </div>
</div>

<style>
.get-started-container {
    width: 100%;
    text-align: center;
}

.get-started-button {
    background: linear-gradient(45deg, #2E8B57, #3CB371);
    color: white;
    font-size: 1.2em;
    padding: 15px 40px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.get-started-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(46, 139, 87, 0.3);
}

.get-started-link {
    text-decoration: none;
}

/* Center align all headings */
.heading {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.heading h1 {
    margin-bottom: 15px;
}

.heading p {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
