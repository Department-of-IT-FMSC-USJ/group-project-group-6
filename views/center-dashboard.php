<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = 'Center Dashboard - EcoCollect Lanka';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Pickup.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

requireLogin();
requireRole('company');

$companyId = getCurrentUserId();

// Add debug output
echo "<!-- Debug: Company ID = $companyId -->";

$company = User::findById($companyId);

if (!$company) {
    die("ERROR: Company user not found! User ID: $companyId");
}

echo "<!-- Debug: Company found = " . $company['full_name'] . " -->";

$pickups = Pickup::getByCompany($companyId);

echo "<!-- Debug: Pickups fetched = " . count($pickups) . " -->";
echo "<!-- Debug: Pickups data = " . print_r($pickups, true) . " -->";

// Calculate statistics
$totalPickups = count($pickups);
$pendingPickups = 0;
$completedPickups = 0;
$totalWeight = 0;

foreach ($pickups as $pickup) {
    if ($pickup['status'] === 'pending') $pendingPickups++;
    if ($pickup['status'] === 'completed') {
        $completedPickups++;
        $totalWeight += floatval($pickup['estimated_weight']);
    }
}

echo "<!-- Debug: About to include header -->";

include __DIR__ . '/includes/header.php';

echo "<!-- Debug: Header included, now rendering dashboard -->";
?>

<main class="dashboard-main">
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1>Collection Center Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($company['full_name']); ?>!</p>
            
            <div style="margin: 20px 0;">
                <a href="/views/add-location.php">
                    <button style="background: #2E8B57; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px;">
                        📍 Add My Location
                    </button>
                </a>
            </div>
            
            <div class="points-display">
                <div class="points-card">
                    <h3>Total Requests</h3>
                    <span class="points-value"><?php echo $totalPickups; ?></span>
                </div>
                <div class="points-card">
                    <h3>Pending</h3>
                    <span class="points-value"><?php echo $pendingPickups; ?></span>
                </div>
                <div class="points-card">
                    <h3>Completed</h3>
                    <span class="points-value"><?php echo $completedPickups; ?></span>
                </div>
                <div class="points-card">
                    <h3>Total Weight</h3>
                    <span class="points-value"><?php echo formatNumber($totalWeight); ?> kg</span>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="grid-template-columns: 1fr;">
            <!-- Allocate Points Form -->
            <div class="dashboard-card">
                <h3>Allocate Points to Customer</h3>
                <form method="POST" action="../controllers/DashboardController.php?action=allocate_points" id="allocatePointsForm">
                    <div class="form-group">
                        <label>Customer Email *</label>
                        <input type="text" name="user_email" id="user_email" placeholder="Enter customer email" required>
                        <small id="email_status" style="display: block; margin-top: 5px;">Search by email to find customer</small>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" required>
                    
                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Weight (kg) *</label>
                            <input type="number" name="weight" step="0.01" min="0.01" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label>Points (auto-calculated)</label>
                            <input type="text" readonly value="5 points per kg" style="background: #f5f5f5;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Description (optional)</label>
                        <textarea name="description" rows="2" placeholder="E.g., PET bottles - 5kg, HDPE containers - 3kg"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Allocate Points</button>
                </form>

                <script>
                // Search for user by email
                document.getElementById('user_email').addEventListener('blur', function() {
                    const email = this.value.trim();
                    const statusMsg = document.getElementById('email_status');
                    
                    if (!email) {
                        statusMsg.textContent = 'Search by email to find customer';
                        statusMsg.style.color = '';
                        return;
                    }
                    
                    statusMsg.textContent = 'Searching...';
                    statusMsg.style.color = 'blue';
                    
                    // Simple AJAX search
                    fetch('../controllers/DashboardController.php?action=search_user&email=' + encodeURIComponent(email))
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.user) {
                                document.getElementById('user_id').value = data.user.id;
                                // Show confirmation
                                const emailInput = document.getElementById('user_email');
                                emailInput.style.borderColor = 'green';
                                statusMsg.textContent = '✓ User found: ' + data.user.name + ' (ID: ' + data.user.id + ')';
                                statusMsg.style.color = 'green';
                                statusMsg.style.fontWeight = 'bold';
                            } else {
                                document.getElementById('user_id').value = '';
                                const emailInput = document.getElementById('user_email');
                                emailInput.style.borderColor = 'red';
                                statusMsg.textContent = '✗ No customer found with this email';
                                statusMsg.style.color = 'red';
                                statusMsg.style.fontWeight = 'bold';
                                alert('No customer found with this email address!');
                            }
                        })
                        .catch(error => {
                            console.error('Error searching user:', error);
                            statusMsg.textContent = '✗ Error searching for user';
                            statusMsg.style.color = 'red';
                            alert('Error searching for user. Please try again.');
                        });
                });
                
                // Validate form before submission
                document.getElementById('allocatePointsForm').addEventListener('submit', function(e) {
                    const userId = document.getElementById('user_id').value;
                    if (!userId || userId === '0' || userId === '') {
                        e.preventDefault();
                        alert('Please enter a valid customer email and wait for the user to be found before submitting!');
                        return false;
                    }
                });
                </script>
            </div>

            <!-- Pickup Requests -->
            <div class="dashboard-card" style="grid-column: 1 / -1;">
                <h3>Pickup Requests</h3>
                <div class="table-container">
                    <?php if (empty($pickups)): ?>
                        <p>No pickup requests yet.</p>
                    <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Weight (est.)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pickups as $pickup): ?>
                                <tr>
                                    <td><?php echo $pickup['id']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($pickup['user_name']); ?><br>
                                        <small>📧 <?php echo htmlspecialchars($pickup['user_email']); ?></small><br>
                                        <small>📞 <?php echo htmlspecialchars($pickup['user_phone']); ?></small>
                                    </td>
                                    <td><?php echo formatDate($pickup['pickup_date']); ?></td>
                                    <td><?php echo $pickup['pickup_time']; ?></td>
                                    <td><?php echo formatNumber($pickup['estimated_weight']); ?> kg</td>
                                    <td>
                                        <span class="status-badge status-<?php echo $pickup['status']; ?>">
                                            <?php echo ucfirst($pickup['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="/controllers/DashboardController.php?action=update_pickup_status" style="display: inline;">
                                            <input type="hidden" name="pickup_id" value="<?php echo $pickup['id']; ?>">
                                            <?php if ($pickup['status'] === 'pending'): ?>
                                                <button type="submit" name="status" value="accepted" class="btn-small btn-success">Accept</button>
                                            <?php elseif ($pickup['status'] === 'accepted'): ?>
                                                <button type="submit" name="status" value="in_progress" class="btn-small btn-info">Start</button>
                                            <?php elseif ($pickup['status'] === 'in_progress'): ?>
                                                <button type="submit" name="status" value="completed" class="btn-small btn-primary">Complete</button>
                                            <?php endif; ?>
                                            <button type="submit" name="status" value="canceled" class="btn-small btn-danger">Cancel</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        </div> <!-- Close dashboard-grid -->
    </div> <!-- Close dashboard-content -->
</main>

<style>
.dashboard-main {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-header h1 {
    color: #2E8B57;
    margin-bottom: 10px;
}

.points-display {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin: 25px 0;
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
    font-size: 13px;
    opacity: 0.9;
    font-weight: normal;
}

.points-value {
    font-size: 28px;
    font-weight: bold;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
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

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2E8B57;
}

.form-group small {
    color: #666;
    font-size: 12px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    transition: all 0.3s;
}

.btn-primary {
    background: #2E8B57;
    color: white;
}

.btn-primary:hover {
    background: #267347;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.data-table th {
    background-color: #2E8B57;
    color: white;
    font-weight: bold;
}

.data-table tr:hover {
    background-color: #f5f5f5;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.status-pending { background: #ffc107; color: #000; }
.status-accepted { background: #17a2b8; color: #fff; }
.status-in_progress { background: #007bff; color: #fff; }
.status-completed { background: #28a745; color: #fff; }
.status-canceled { background: #dc3545; color: #fff; }

.btn-small {
    padding: 5px 10px;
    font-size: 12px;
    margin: 2px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-success { background: #28a745; color: white; }
.btn-info { background: #17a2b8; color: white; }
.btn-danger { background: #dc3545; color: white; }

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .points-display {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
