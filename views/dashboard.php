<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = 'Dashboard - EcoCollect Lanka';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Points.php';
require_once __DIR__ . '/../models/Pickup.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

requireLogin();

$userId = getCurrentUserId();
$userType = getCurrentUserType();

// Redirect company users to center dashboard
if ($userType === 'company') {
    redirect('/views/center-dashboard.php');
    exit();
}

// Get user data
$pointsBalance = Points::getBalance($userId);
$pointsHistory = Points::getHistory($userId, 10);
$pickups = Pickup::getByUser($userId);
$totalEarned = Points::getTotalEarned($userId);

// Calculate total collected weight
$totalWeight = 0;
foreach ($pickups as $pickup) {
    if ($pickup['status'] === 'completed') {
        $totalWeight += floatval($pickup['estimated_weight']);
    }
}

include __DIR__ . '/includes/header.php';
?>

<main class="dashboard-main">
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1><?php echo ucfirst($userType); ?> Dashboard</h1>
            <div class="points-display">
                <div class="points-card">
                    <h3>Current Points</h3>
                    <span class="points-value"><?php echo number_format($pointsBalance); ?></span>
                </div>
                <div class="points-card">
                    <h3>Total Collected</h3>
                    <span class="points-value"><?php echo formatNumber($totalWeight); ?> kg</span>
                </div>
                <div class="points-card">
                    <h3>Total Earned</h3>
                    <span class="points-value"><?php echo number_format($totalEarned); ?> pts</span>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 1fr;">
            <div class="dashboard-card">
                <h3>Environmental Impact</h3>
                <div class="impact-stats">
                    <div class="impact-item">
                        <span class="impact-icon">🌱</span>
                        <div>
                            <span class="impact-number"><?php echo formatNumber($totalWeight * 0.058); ?></span>
                            <span class="impact-label">Trees Equivalent Saved</span>
                        </div>
                    </div>
                    <div class="impact-item">
                        <span class="impact-icon">🌊</span>
                        <div>
                            <span class="impact-number"><?php echo formatNumber($totalWeight * 450); ?></span>
                            <span class="impact-label">Liters of Water Saved</span>
                        </div>
                    </div>
                    <div class="impact-item">
                        <span class="impact-icon">💨</span>
                        <div>
                            <span class="impact-number"><?php echo formatNumber($totalWeight * 2.5); ?></span>
                            <span class="impact-label">kg CO₂ Reduced</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 1fr;">
            <div class="dashboard-card">
                <h3>Recent Points History</h3>
                <div class="collections-list">
                    <?php if (empty($pointsHistory)): ?>
                        <p>No points history yet. Start recycling to earn points!</p>
                    <?php else: ?>
                        <?php foreach ($pointsHistory as $point): ?>
                            <div class="collection-item">
                                <span class="collection-date"><?php echo formatDate($point['created_at'], 'Y-m-d H:i'); ?></span>
                                <span class="collection-description"><?php echo htmlspecialchars($point['description']); ?></span>
                                <?php if ($point['points_earned'] > 0): ?>
                                    <span class="collection-points" style="color: green;">+<?php echo $point['points_earned']; ?> points</span>
                                <?php else: ?>
                                    <span class="collection-points" style="color: red;">-<?php echo $point['points_used']; ?> points</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dashboard-card">
                <h3>Pickup Requests</h3>
                <div class="scheduled-list">
                    <?php if (empty($pickups)): ?>
                        <p>No pickup requests yet.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($pickups, 0, 5) as $pickup): ?>
                            <div class="scheduled-item">
                                <span class="scheduled-date"><?php echo formatDate($pickup['pickup_date']); ?></span>
                                <span class="scheduled-time"><?php echo $pickup['pickup_time']; ?></span>
                                <span class="scheduled-company"><?php echo htmlspecialchars($pickup['company_name']); ?></span>
                                <span class="scheduled-status <?php echo $pickup['status']; ?>">
                                    <?php echo ucfirst($pickup['status']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.dashboard-main {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-header h1 {
    color: #2E8B57;
    margin-bottom: 20px;
}

.points-display {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.points-card {
    padding: 20px;
    background: linear-gradient(135deg, #2E8B57 0%, #20B2AA 100%);
    color: white;
    border-radius: 10px;
    text-align: center;
}

.points-card h3 {
    margin: 0 0 10px 0;
    font-size: 14px;
    opacity: 0.9;
}

.points-value {
    font-size: 32px;
    font-weight: bold;
    display: block;
}

.dashboard-grid {
    display: grid;
    gap: 25px;
    margin-bottom: 30px;
}

.dashboard-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.dashboard-card h3 {
    color: #2E8B57;
    margin-top: 0;
    margin-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 12px;
    font-size: 18px;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.action-buttons button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
}

.btn-primary {
    background: #2E8B57;
    color: white;
}

.btn-secondary {
    background: #20B2AA;
    color: white;
}

.collections-list, .scheduled-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.collection-item, .scheduled-item {
    padding: 10px;
    background: #f8f9fa;
    border-left: 3px solid #2E8B57;
    border-radius: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.collection-date, .scheduled-date {
    font-size: 12px;
    color: #666;
}

.collection-description {
    flex: 1;
    padding: 0 10px;
}

.collection-points {
    font-weight: bold;
}

.scheduled-status {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.scheduled-status.pending { background: #ffc107; color: #000; }
.scheduled-status.accepted { background: #17a2b8; color: #fff; }
.scheduled-status.in_progress { background: #007bff; color: #fff; }
.scheduled-status.completed { background: #28a745; color: #fff; }

.impact-stats {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.impact-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 10px;
    background: #f0f8f5;
    border-radius: 8px;
}

.impact-icon {
    font-size: 32px;
}

.impact-number {
    font-size: 24px;
    font-weight: bold;
    color: #2E8B57;
    display: block;
}

.impact-label {
    font-size: 12px;
    color: #666;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
