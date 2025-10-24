<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pageTitle = 'Add My Location - EcoCollect Lanka';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

requireLogin();
requireRole('company');

$userId = getCurrentUserId();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $type = $_POST['type'] ?? 'drop-off';
    $address = $_POST['address'] ?? '';
    $district = $_POST['district'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $hours = $_POST['hours'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($name) || empty($address) || empty($district)) {
        setFlashMessage('Name, address, and district are required!', 'error');
    } else {
        $locationData = [
            'user_id' => $userId, // Set the owner to current company user
            'name' => $name,
            'type' => $type,
            'address' => $address,
            'district' => $district,
            'city' => $district,
            'phone' => $phone,
            'hours' => $hours,
            'description' => $description,
            'verified' => 1
        ];
        
        $locationId = Location::create($locationData);
        
        if ($locationId) {
            setFlashMessage('Location added successfully! Users can now see it and request pickups.', 'success');
            redirect('/views/center-dashboard.php');
        } else {
            setFlashMessage('Failed to add location. Please try again.', 'error');
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<style>
.location-form-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 30px;
    background: white;
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
    box-shadow: 0 0 0 2px rgba(46, 139, 87, 0.1);
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

.required {
    color: red;
}
</style>

<div class="location-form-container">
    <div class="form-header">
        <h1>Add Your Collection Center Location</h1>
        <p>Add your center's location so households can find you and request pickups</p>
    </div>

    <div class="info-box">
        <h3>📍 Why Add Your Location?</h3>
        <ul>
            <li>Households can see your center in the Locations page</li>
            <li>Users can request pickups from your center</li>
            <li>Increase your visibility and reach more customers</li>
            <li>Help the community recycle more effectively</li>
        </ul>
    </div>

    <form method="POST">
        <div class="form-group">
            <label for="name">Center Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" required 
                placeholder="e.g., Green Recycling Center - Colombo Branch">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="type">Location Type <span class="required">*</span></label>
                <select id="type" name="type" required>
                    <option value="drop-off">Drop-off Center</option>
                    <option value="pickup">Pickup Service</option>
                    <option value="recycling">Recycling Facility</option>
                    <option value="community">Community Center</option>
                </select>
            </div>

            <div class="form-group">
                <label for="district">District <span class="required">*</span></label>
                <select id="district" name="district" required>
                    <option value="">-- Select District --</option>
                    <option value="Colombo">Colombo</option>
                    <option value="Gampaha">Gampaha</option>
                    <option value="Kalutara">Kalutara</option>
                    <option value="Kandy">Kandy</option>
                    <option value="Matale">Matale</option>
                    <option value="Nuwara Eliya">Nuwara Eliya</option>
                    <option value="Galle">Galle</option>
                    <option value="Matara">Matara</option>
                    <option value="Hambantota">Hambantota</option>
                    <option value="Jaffna">Jaffna</option>
                    <option value="Kilinochchi">Kilinochchi</option>
                    <option value="Mannar">Mannar</option>
                    <option value="Vavuniya">Vavuniya</option>
                    <option value="Mullaitivu">Mullaitivu</option>
                    <option value="Batticaloa">Batticaloa</option>
                    <option value="Ampara">Ampara</option>
                    <option value="Trincomalee">Trincomalee</option>
                    <option value="Kurunegala">Kurunegala</option>
                    <option value="Puttalam">Puttalam</option>
                    <option value="Anuradhapura">Anuradhapura</option>
                    <option value="Polonnaruwa">Polonnaruwa</option>
                    <option value="Badulla">Badulla</option>
                    <option value="Moneragala">Moneragala</option>
                    <option value="Ratnapura">Ratnapura</option>
                    <option value="Kegalle">Kegalle</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="address">Full Address <span class="required">*</span></label>
            <textarea id="address" name="address" required 
                placeholder="Enter complete address with street name, city, and postal code"></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone">Contact Phone</label>
                <input type="tel" id="phone" name="phone" 
                    placeholder="e.g., 011-2345678">
            </div>

            <div class="form-group">
                <label for="hours">Operating Hours</label>
                <input type="text" id="hours" name="hours" 
                    placeholder="e.g., Mon-Sat: 8AM-6PM">
            </div>
        </div>

        <div class="form-group">
            <label for="description">Description / Services Offered</label>
            <textarea id="description" name="description" 
                placeholder="Describe your services, accepted plastic types, special offers, etc."></textarea>
        </div>

        <button type="submit" class="btn-submit">Add Location</button>
    </form>

    <div style="margin-top: 20px; text-align: center;">
        <a href="/views/center-dashboard.php" style="color: #2E8B57;">← Back to Dashboard</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
