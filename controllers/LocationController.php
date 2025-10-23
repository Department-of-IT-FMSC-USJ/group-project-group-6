<?php
/**
 * Location Controller
 * Handles location search and management
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Location.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

class LocationController {
    
    /**
     * Show all locations
     */
    public static function showLocations() {
        // Get search parameters
        $district = $_GET['district'] ?? '';
        $type = $_GET['type'] ?? '';
        
        // Search or get all locations
        if (!empty($district) || !empty($type)) {
            $locations = Location::search($district, $type);
        } else {
            $locations = Location::getVerified();
        }
        
        require __DIR__ . '/../views/location.php';
    }
    
    /**
     * Register a new center (self-registration)
     */
    public static function registerCenter() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/views/register-center.php');
            return;
        }
        
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? 'drop-off';
        $address = $_POST['address'] ?? '';
        $district = $_POST['district'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $hours = $_POST['hours'] ?? '';
        $description = $_POST['description'] ?? '';
        $latitude = $_POST['latitude'] ?? 0;
        $longitude = $_POST['longitude'] ?? 0;
        
        // Validate
        if (empty($name) || empty($address)) {
            setFlashMessage('Name and address are required!', 'error');
            redirect('/views/register-center.php');
            return;
        }
        
        $locationData = [
            'name' => $name,
            'type' => $type,
            'address' => $address,
            'district' => $district,
            'phone' => $phone,
            'hours' => $hours,
            'description' => $description,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        
        $locationId = Location::create($locationData);
        
        if ($locationId) {
            setFlashMessage('Center registered successfully! Awaiting verification.', 'success');
            redirect('/views/location.php');
        } else {
            setFlashMessage('Failed to register center!', 'error');
            redirect('/views/register-center.php');
        }
    }
    
    /**
     * Get locations as JSON (for AJAX requests)
     */
    public static function getLocationsJSON() {
        header('Content-Type: application/json');
        
        $district = $_GET['district'] ?? '';
        $type = $_GET['type'] ?? '';
        
        if (!empty($district) || !empty($type)) {
            $locations = Location::search($district, $type);
        } else {
            $locations = Location::getVerified();
        }
        
        echo json_encode($locations);
        exit();
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'register_center':
            LocationController::registerCenter();
            break;
        case 'get_json':
            LocationController::getLocationsJSON();
            break;
    }
}
?>
