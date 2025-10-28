<?php
/**
 * Redemption Controller
 * Handles points redemption operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Redemption.php';
require_once __DIR__ . '/../models/Points.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

class RedemptionController {
    
    /**
     * Show redemption page
     */
    public static function showRedemptionPage() {
        requireLogin();
        
        $userId = getCurrentUserId();
        $pointsBalance = Points::getBalance($userId);
        $redemptions = Redemption::getByUser($userId);
        
        require __DIR__ . '/../views/redeem.php';
    }
    
    /**
     * Process redemption
     */
    public static function processRedemption() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/views/redeem.php');
            return;
        }
        
        $userId = getCurrentUserId();
        $pointsToRedeem = intval($_POST['points'] ?? 0);
        $rewardType = $_POST['reward_type'] ?? '';
        $rewardDescription = $_POST['reward_description'] ?? '';
        
        // Validate
        if ($pointsToRedeem <= 0) {
            setFlashMessage('Invalid points amount!', 'error');
            redirect('/views/redeem.php');
            return;
        }
        
        // Check if user has enough points
        $currentBalance = Points::getBalance($userId);
        if ($currentBalance < $pointsToRedeem) {
            setFlashMessage('Insufficient points!', 'error');
            redirect('/views/redeem.php');
            return;
        }
        
        // Create redemption record
        $redemptionData = [
            'user_id' => $userId,
            'points_redeemed' => $pointsToRedeem,
            'reward_description' => $rewardDescription
        ];
        
        $redemptionId = Redemption::create($redemptionData);
        
        if ($redemptionId) {
            // Deduct points
            Points::use($userId, $pointsToRedeem, "Redeemed: $rewardDescription");
            
            setFlashMessage('Points redeemed successfully!', 'success');
        } else {
            setFlashMessage('Failed to redeem points!', 'error');
        }
        
        redirect('/views/redeem.php');
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'redeem':
            RedemptionController::processRedemption();
            break;
    }
}
?>
