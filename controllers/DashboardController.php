<?php
/**
 * Dashboard Controller
 * Handles dashboard operations for different user types
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Pickup.php';
require_once __DIR__ . '/../models/Points.php';
require_once __DIR__ . '/../models/Redemption.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

class DashboardController {
    
    /**
     * Show user dashboard (household/business)
     */
    public static function showUserDashboard() {
        requireLogin();
        
        $userId = getCurrentUserId();
        $userType = getCurrentUserType();
        
        // Redirect if company admin
        if ($userType === 'company') {
            redirect('/views/center-dashboard.php');
            return;
        }
        
        // Get user data
        $user = User::findById($userId);
        $pointsBalance = Points::getBalance($userId);
        $pointsHistory = Points::getHistory($userId, 10);
        $pickups = Pickup::getByUser($userId);
        $redemptions = Redemption::getByUser($userId);
        $totalEarned = Points::getTotalEarned($userId);
        $totalUsed = Points::getTotalUsed($userId);
        
        // Calculate total collected weight (from completed pickups)
        $totalWeight = 0;
        foreach ($pickups as $pickup) {
            if ($pickup['status'] === 'completed') {
                $totalWeight += floatval($pickup['estimated_weight']);
            }
        }
        
        require __DIR__ . '/../views/dashboard.php';
    }
    
    /**
     * Show center admin dashboard
     */
    public static function showCenterDashboard() {
        requireLogin();
        requireRole('company');
        
        $companyId = getCurrentUserId();
        
        // Get company data
        $company = User::findById($companyId);
        $pickups = Pickup::getByCompany($companyId);
        
        // Calculate statistics
        $totalPickups = count($pickups);
        $pendingPickups = 0;
        $completedPickups = 0;
        $totalWeight = 0;
        
        foreach ($pickups as $pickup) {
            if ($pickup['status'] === 'pending') {
                $pendingPickups++;
            }
            if ($pickup['status'] === 'completed') {
                $completedPickups++;
                $totalWeight += floatval($pickup['estimated_weight']);
            }
        }
        
        require __DIR__ . '/../views/center-dashboard.php';
    }
    
    /**
     * Handle points allocation (center admin)
     */
    public static function allocatePoints() {
        requireLogin();
        requireRole('company');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/views/center-dashboard.php');
            return;
        }
        
        $userId = intval($_POST['user_id'] ?? 0);
        $weight = floatval($_POST['weight'] ?? 0);
        $description = $_POST['description'] ?? '';
        
        if ($userId <= 0 || $weight <= 0) {
            setFlashMessage('Invalid data provided!', 'error');
            redirect('/views/center-dashboard.php');
            return;
        }
        
        // Calculate points
        $points = calculatePoints($weight);
        
        // Add points
        $pointsId = Points::add($userId, $points, $description);
        
        if ($pointsId) {
            setFlashMessage("Successfully added $points points to user!", 'success');
        } else {
            setFlashMessage('Failed to add points!', 'error');
        }
        
        redirect('/views/center-dashboard.php');
    }
    
    /**
     * Search for user by email (AJAX endpoint)
     */
    public static function searchUser() {
        requireLogin();
        requireRole('company');
        
        header('Content-Type: application/json');
        
        $email = $_GET['email'] ?? '';
        
        if (empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Email is required']);
            return;
        }
        
        $user = User::findByEmail($email);
        
        if ($user && $user['user_type'] === 'household') {
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['full_name'],
                    'email' => $user['email']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found or not a household user']);
        }
    }
    
    /**
     * Handle pickup status update (center admin)
     */
    public static function updatePickupStatus() {
        requireLogin();
        requireRole('company');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/views/center-dashboard.php');
            return;
        }
        
        $pickupId = intval($_POST['pickup_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        
        if ($pickupId <= 0 || empty($status)) {
            setFlashMessage('Invalid data provided!', 'error');
            redirect('/views/center-dashboard.php');
            return;
        }
        
        if (Pickup::updateStatus($pickupId, $status)) {
            setFlashMessage('Pickup status updated successfully!', 'success');
        } else {
            setFlashMessage('Failed to update pickup status!', 'error');
        }
        
        redirect('/views/center-dashboard.php');
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'allocate_points':
            DashboardController::allocatePoints();
            break;
        case 'update_pickup_status':
            DashboardController::updatePickupStatus();
            break;
        case 'search_user':
            DashboardController::searchUser();
            break;
    }
}
?>
