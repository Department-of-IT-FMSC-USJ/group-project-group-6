<?php
$pageTitle = 'How It Works - EcoCollect Lanka';
include __DIR__ . '/includes/header.php';
?>

<main class="page-content">
    <div class="container">
        <section class="page-hero">
            <h1>How It Works</h1>
            <p>Simple steps to start making a difference and earning rewards</p>
        </section>

        <section class="process-section">
            <h2>For Households & Individuals</h2>
            <div class="process-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Sign Up</h3>
                    <p>Create your account by providing basic information. Choose your location and set your preferences. It only takes 2 minutes!</p>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Collect & Sort</h3>
                    <p>Gather your plastic waste and sort it according to type (PET, HDPE, PP, etc.). Clean containers work best for maximum point value.</p>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Request Pickup or Drop-off</h3>
                    <p>Use our platform to schedule a pickup at your convenience, or find the nearest drop-off center on our location map.</p>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Collection & Weighing</h3>
                    <p>Our authorized partners collect your plastic waste, weigh it accurately, and update your account with earned points in real-time.</p>
                </div>

                <div class="step">
                    <div class="step-number">5</div>
                    <h3>Earn & Track Points</h3>
                    <p>Watch your points accumulate with each collection. Track your environmental impact and see how much you've contributed!</p>
                    <p><strong>Rate: 5 points per kilogram</strong></p>
                </div>

                <div class="step">
                    <div class="step-number">6</div>
                    <h3>Redeem Rewards</h3>
                    <p>Use your points for rewards and discounts at affiliated brands (coming soon). Points will be automatically deducted when you shop.</p>
                </div>
            </div>
        </section>

        <section class="process-section">
            <h2>For Collection Centers</h2>
            <div class="process-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Register Your Center</h3>
                    <p>Sign up as a collection center and provide your location details, operating hours, and services offered.</p>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Receive Pickup Requests</h3>
                    <p>Get notified when users in your area request pickups. View request details and accept or decline based on your capacity.</p>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Collect & Weigh</h3>
                    <p>Collect plastic waste from users, weigh it accurately using certified scales, and record the collection details.</p>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Allocate Points</h3>
                    <p>Log into your center dashboard and allocate points to users based on the weight collected. System automatically calculates points (5 pts/kg).</p>
                </div>

                <div class="step">
                    <div class="step-number">5</div>
                    <h3>Manage & Track</h3>
                    <p>Use your dashboard to manage all requests, track collections, view statistics, and optimize your operations.</p>
                </div>
            </div>
        </section>

        <section class="content-section">
            <h2>Plastic Types We Accept</h2>
            <div class="plastic-types">
                <div class="plastic-card">
                    <h4>PET (1)</h4>
                    <p>Water bottles, soda bottles, food containers</p>
                </div>
                <div class="plastic-card">
                    <h4>HDPE (2)</h4>
                    <p>Milk jugs, detergent bottles, shampoo bottles</p>
                </div>
                <div class="plastic-card">
                    <h4>PP (5)</h4>
                    <p>Yogurt containers, medicine bottles, bottle caps</p>
                </div>
                <div class="plastic-card">
                    <h4>PS (6)</h4>
                    <p>Disposable cups, food containers, CD cases</p>
                </div>
                <div class="plastic-card">
                    <h4>Plastic Films</h4>
                    <p>Shopping bags, plastic wrap, bubble wrap</p>
                </div>
                <div class="plastic-card">
                    <h4>Mixed</h4>
                    <p>Various plastic types combined</p>
                </div>
            </div>
        </section>

        <section class="content-section">
            <h2>Points & Rewards</h2>
            <div class="rewards-info">
                <h3>How Points Work</h3>
                <ul>
                    <li><strong>Earn:</strong> 5 points for every kilogram of plastic recycled</li>
                    <li><strong>Track:</strong> View your points balance and history in your dashboard</li>
                    <li><strong>Redeem:</strong> Use points for discounts and rewards (affiliated brands coming soon)</li>
                </ul>
                <br>
                <br>

                <h3>Discount Tiers (Coming Soon)</h3>
                <ul>
                    <li>🎁 300 points = 10% discount at partner stores</li>
                    <li>🎁 500 points = 15% discount at partner stores</li>
                    <li>🎁 1000 points = 20% discount at partner stores</li>
                </ul>
            </div>
        </section>

        <?php if (!$is_logged_in): ?>
            <section class="cta-section" style="text-align: center; padding: 60px 20px; background: linear-gradient(135deg, #2E8B57 0%, #20B2AA 100%); border-radius: 10px; color: white; margin: 40px 0; overflow: hidden;">
                <div style="max-width: 600px; margin: 0 auto; display: flex; flex-direction: column; align-items: center; gap: 25px;">
                    <h2 style="color: white; font-size: 2.5em; margin: 0; line-height: 1.2; letter-spacing: -0.5px;">Ready to Make a Difference?</h2>
                    <p style="font-size: 18px; line-height: 1.6; margin: 0; opacity: 0.9;color: white;">Join thousands of Sri Lankans earning rewards while saving the environment</p>
                    <a href="/views/register.php" style="display: inline-block; background: white; color: #2E8B57; padding: 15px 40px; font-size: 18px; font-weight: bold; text-decoration: none; border-radius: 30px; transition: all 0.3s ease; border: 2px solid transparent; margin-top: 10px; cursor: pointer;" onmouseover="this.style.background='transparent'; this.style.color='white'; this.style.borderColor='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.background='white'; this.style.color='#2E8B57'; this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">Sign Up Now</a>
                </div>
            </section>
        <?php endif; ?>
    </div>
</main>

<style>
.process-steps {
    display: grid;
    gap: 30px;
    margin: 30px 0;
}

.step {
    padding: 25px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 5px solid #2E8B57;
}

.step-number {
    display: inline-block;
    width: 50px;
    height: 50px;
    background: #2E8B57;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 50px;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
}

.plastic-types, .approach-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.plastic-card {
    padding: 20px;
    background: white;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    text-align: center;
}

.plastic-card h4 {
    color: #2E8B57;
    margin-bottom: 10px;
}

.rewards-info {
    background: #f0f8f5;
    padding: 30px;
    border-radius: 10px;
    margin: 20px 0;
}

.rewards-info ul {
    list-style: none;
    padding-left: 0;
}

.rewards-info ul li {
    padding: 10px 0;
    border-bottom: 1px solid #e0e0e0;
}

.rewards-info ul li:last-child {
    border-bottom: none;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
