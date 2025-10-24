<?php
/**
 * Pickup Controller
 * Handles pickup request operations
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Pickup.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

class PickupController {
    
    /**
     * Create a new pickup request
     */
    public static function createPickup() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/views/dashboard.php');
            return;
        }
        
        $userId = getCurrentUserId();
        $locationId = !empty($_POST['location_id']) ? intval($_POST['location_id']) : null;
        $estimatedWeight = floatval($_POST['estimated_weight'] ?? 0);
        $preferredDate = $_POST['preferred_date'] ?? '';
        $pickupAddress = $_POST['pickup_address'] ?? '';
        $specialInstructions = $_POST['special_instructions'] ?? '';
        $plasticTypes = isset($_POST['plastic_types']) ? implode(', ', $_POST['plastic_types']) : '';
        
        // Validate
        if (empty($locationId) || empty($estimatedWeight) || empty($preferredDate) || empty($pickupAddress)) {
            setFlashMessage('All required fields must be filled!', 'error');
            redirect('/views/request-pickup.php');
            return;
        }
        
        $pickupData = [
            'user_id' => $userId,
            'location_id' => $locationId,
            'pickup_date' => $preferredDate,
            'pickup_time' => '00:00:00', // Default time
            'estimated_weight' => $estimatedWeight,
            'pickup_address' => $pickupAddress,
            'special_instructions' => $specialInstructions,
            'plastic_types' => $plasticTypes,
            'status' => 'pending'
        ];
        
        $pickupId = Pickup::create($pickupData);
        
        if ($pickupId) {
            setFlashMessage('Pickup request submitted successfully! You will earn ' . ($estimatedWeight * 5) . ' points when completed.', 'success');
            redirect('/views/dashboard.php');
        } else {
            setFlashMessage('Failed to submit pickup request!', 'error');
            redirect('/views/request-pickup.php');
        }
    }
    
    /**
     * Cancel a pickup request
     */
    public static function cancelPickup() {
        requireLogin();
        
        $pickupId = intval($_GET['id'] ?? 0);
        
        if ($pickupId <= 0) {
            setFlashMessage('Invalid pickup request!', 'error');
            redirect('/views/dashboard.php');
            return;
        }
        
        // Verify ownership
        $pickup = Pickup::findById($pickupId);
        if (!$pickup || $pickup['user_id'] != getCurrentUserId()) {
            setFlashMessage('Unauthorized access!', 'error');
            redirect('/views/dashboard.php');
            return;
        }
        
        // Update status to canceled
        if (Pickup::updateStatus($pickupId, 'canceled')) {
            setFlashMessage('Pickup request canceled successfully!', 'success');
        } else {
            setFlashMessage('Failed to cancel pickup request!', 'error');
        }
        
        redirect('/views/dashboard.php');
    }
    
    /**
     * Show pickup request form
     */
    public static function showRequestForm() {
        requireLogin();
        
        // Get all collection companies
        $companies = User::getAllByType('company');
        
        require __DIR__ . '/../views/request-pickup.php';
    }
}

// Handle actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        PickupController::createPickup();
        break;
    case 'cancel':
        PickupController::cancelPickup();
        break;
    default:
        redirect('/views/dashboard.php');
        break;
}
?>
