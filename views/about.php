<?php
$pageTitle = 'About Us - EcoCollect Lanka';
include __DIR__ . '/includes/header.php';
?>

<main class="page-content">
    <div class="container">
        <section class="page-hero">
            <h1>About EcoCollect Lanka</h1>
            <p>Leading the digital transformation of plastic waste management in Sri Lanka</p>
        </section>

        <section class="content-section">
            <h2>The Plastic Crisis in Sri Lanka</h2>
            <div class="content-grid">
                <div class="content-text">
                    <p>Sri Lanka faces a significant plastic waste challenge, with over 1.6 million tons of solid waste generated annually, of which plastic constitutes approximately 7-8%. Unfortunately, only a small percentage of this plastic waste is properly recycled or disposed of, leading to:</p>
                    <ul>
                        <li>Environmental pollution in waterways and coastal areas</li>
                        <li>Health hazards for communities</li>
                        <li>Loss of tourism appeal in natural areas</li>
                        <li>Wasted economic opportunities in the recycling sector</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="content-section">
            <h2>Our Vision and Mission</h2>
            <div class="vision-mission">
                <div class="vision-card">
                    <h3>🎯 Our Vision</h3>
                    <p>To create a Sri Lanka where plastic waste is systematically collected, recycled, and transformed into valuable resources, contributing to a circular economy and sustainable future.</p>
                </div>
                <div class="mission-card">
                    <h3>🚀 Our Mission</h3>
                    <p>To build a comprehensive digital platform that connects households and authorized collection companies, making plastic waste collection efficient, rewarding, and environmentally impactful.</p>
                </div>
            </div>
        </section>

        <section class="content-section">
            <h2>Global Success Stories</h2>
            <div class="success-stories">
                <div class="story-card">
                    <h4>🇩🇪 Germany's Green Dot System</h4>
                    <p>Germany's packaging waste collection system has achieved over 67% recycling rate through systematic collection and producer responsibility programs.</p>
                </div>
                <div class="story-card">
                    <h4>🇹🇼 Taiwan's Circular Economy</h4>
                    <p>Taiwan has reached a 55% recycling rate through comprehensive waste sorting and collection systems integrated with digital technology.</p>
                </div>
                <div class="story-card">
                    <h4>🇳🇱 Netherlands' Extended Producer Responsibility</h4>
                    <p>The Netherlands has implemented successful plastic collection programs that involve both consumers and producers in the recycling chain.</p>
                </div>
            </div>
        </section>

        <section class="content-section">
            <h2>Our Approach</h2>
            <div class="approach-grid">
                <div class="approach-item">
                    <div class="approach-icon">🔗</div>
                    <h4>Digital Connectivity</h4>
                    <p>Connecting all stakeholders through an intuitive digital platform that makes plastic collection seamless and transparent.</p>
                </div>
                <div class="approach-item">
                    <div class="approach-icon">🎁</div>
                    <h4>Incentive System</h4>
                    <p>Rewarding participants with points that can be redeemed for rewards, creating motivation for continued participation.</p>
                </div>
                <div class="approach-item">
                    <div class="approach-icon">🤝</div>
                    <h4>Community Engagement</h4>
                    <p>Building a community of environmentally conscious citizens working together for a common goal.</p>
                </div>
                <div class="approach-item">
                    <div class="approach-icon">📊</div>
                    <h4>Data-Driven Insights</h4>
                    <p>Using data to optimize collection routes, track impact, and improve system efficiency.</p>
                </div>
            </div>
        </section>

        <section class="content-section">
            <h2>Join Our Mission</h2>
            <p>Whether you're a household looking to make a difference, or a collection center wanting to expand your reach, EcoCollect Lanka provides the tools and platform to make plastic recycling accessible and rewarding for everyone.</p>
            
            <?php if (!$is_logged_in): ?>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="/views/register.php"><button class="btn btn-primary" style="padding: 15px 40px; font-size: 18px;">Get Started Today</button></a>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
