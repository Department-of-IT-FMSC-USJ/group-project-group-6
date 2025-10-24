<?php
$pageTitle = 'Request Pickup - EcoCollect Lanka';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

requireLogin();

$userId = getCurrentUserId();
$selectedCenter = $_GET['center'] ?? '';

// Get all collection centers
$centers = Location::getAll();

include __DIR__ . '/includes/header.php';
?>

<style>
.pickup-form-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 30px;
    background:white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #2E8B57;
}

.form-header h1 {
    color: #2E8B57;
    margin-bottom: 10px;
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
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    font-family: Arial, sans-serif;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2E8B57;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.btn-submit {
    background: #2E8B57;
    color: white;
    padding: 14px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    transition: background 0.3s;
}

.btn-submit:hover {
    background: #25704a;
}

.plastic-types {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.plastic-type-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.plastic-type-item input[type="checkbox"] {
    width: auto;
}

.info-box {
    background: #e8f5e9;
    border-left: 4px solid #2E8B57;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.info-box h3 {
    color: #2E8B57;
    margin-bottom: 10px;
}
</style>

<div class="pickup-form-container">
    <div class="form-header">
        <h1>Request Pickup</h1>
        <p>Fill in the details below to schedule a pickup for your plastic waste</p>
    </div>

    <div class="info-box">
        <h3>📋 Before You Request:</h3>
        <ul>
            <li>Ensure plastics are clean and dry</li>
            <li>Separate different types of plastics if possible</li>
            <li>Provide accurate weight estimates</li>
            <li>You earn 5 points per kilogram!</li>
        </ul>
    </div>

    <form action="/controllers/PickupController.php" method="POST">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

        <div class="form-group">
            <label for="location_id">Select Collection Center *</label>
            <select id="location_id" name="location_id" required>
                <option value="">-- Choose a center --</option>
                <?php foreach ($centers as $center): ?>
                    <option value="<?php echo $center['id']; ?>" 
                        <?php echo ($selectedCenter == $center['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($center['name']); ?> 
                        (<?php echo htmlspecialchars($center['district']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="estimated_weight">Estimated Weight (kg) *</label>
                <input type="number" id="estimated_weight" name="estimated_weight" 
                    step="0.1" min="0.1" required placeholder="e.g., 5.5">
            </div>

            <div class="form-group">
                <label for="preferred_date">Preferred Pickup Date *</label>
                <input type="date" id="preferred_date" name="preferred_date" 
                    min="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Plastic Types (select all that apply)</label>
            <div class="plastic-types">
                <div class="plastic-type-item">
                    <input type="checkbox" id="pet" name="plastic_types[]" value="PET">
                    <label for="pet">PET (Bottles)</label>
                </div>
                <div class="plastic-type-item">
                    <input type="checkbox" id="hdpe" name="plastic_types[]" value="HDPE">
                    <label for="hdpe">HDPE (Containers)</label>
                </div>
                <div class="plastic-type-item">
                    <input type="checkbox" id="pvc" name="plastic_types[]" value="PVC">
                    <label for="pvc">PVC (Pipes)</label>
                </div>
                <div class="plastic-type-item">
                    <input type="checkbox" id="ldpe" name="plastic_types[]" value="LDPE">
                    <label for="ldpe">LDPE (Bags)</label>
                </div>
                <div class="plastic-type-item">
                    <input type="checkbox" id="pp" name="plastic_types[]" value="PP">
                    <label for="pp">PP (Containers)</label>
                </div>
                <div class="plastic-type-item">
                    <input type="checkbox" id="ps" name="plastic_types[]" value="PS">
                    <label for="ps">PS (Foam)</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="pickup_address">Pickup Address *</label>
            <textarea id="pickup_address" name="pickup_address" required 
                placeholder="Enter your complete address for pickup"></textarea>
        </div>

        <div class="form-group">
            <label for="special_instructions">Special Instructions (Optional)</label>
            <textarea id="special_instructions" name="special_instructions" 
                placeholder="Any special instructions for the pickup team..."></textarea>
        </div>

        <button type="submit" class="btn-submit">Submit Pickup Request</button>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
