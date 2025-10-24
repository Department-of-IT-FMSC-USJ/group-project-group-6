<?php
/**
 * Redemption Model
 * Handles all redemption-related database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/utils.php';

class Redemption {
    
    /**
     * Create a new redemption
     * @param array $data Redemption data
     * @return int|false Redemption ID or false on failure
     */
    public static function create($data) {
        $conn = getDBConnection();
        
        $user_id = intval($data['user_id']);
        $points_redeemed = intval($data['points_redeemed']);
        $reward_description = sanitize($data['reward_description']);
        
        $sql = "INSERT INTO redemptions (user_id, points_redeemed, reward_description) 
                VALUES ($user_id, $points_redeemed, '$reward_description')";
        
        if (mysqli_query($conn, $sql)) {
            $redemption_id = mysqli_insert_id($conn);
            closeDBConnection($conn);
            return $redemption_id;
        }
        
        closeDBConnection($conn);
        return false;
    }
    
    /**
     * Get all redemptions for a user
     * @param int $userId User ID
     * @return array List of redemptions
     */
    public static function getByUser($userId) {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM redemptions WHERE user_id = $userId ORDER BY redeemed_at DESC";
        
        $result = mysqli_query($conn, $sql);
        
        $redemptions = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $redemptions[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $redemptions;
    }
    
    /**
     * Get all redemptions (for admin)
     * @param int $limit Limit results
     * @return array List of redemptions
     */
    public static function getAll($limit = 100) {
        $conn = getDBConnection();
        
        $sql = "SELECT r.*, u.full_name, u.email 
                FROM redemptions r
                LEFT JOIN users u ON r.user_id = u.id
                ORDER BY r.redeemed_at DESC
                LIMIT $limit";
        
        $result = mysqli_query($conn, $sql);
        
        $redemptions = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $redemptions[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $redemptions;
    }
    
    /**
     * Get total redeemed points for a user
     * @param int $userId User ID
     * @return int Total redeemed points
     */
    public static function getTotalRedeemed($userId) {
        $conn = getDBConnection();
        
        $sql = "SELECT COALESCE(SUM(points_redeemed), 0) as total FROM redemptions WHERE user_id = $userId";
        
        $result = mysqli_query($conn, $sql);
        
        $total = 0;
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $total = intval($row['total']);
        }
        
        closeDBConnection($conn);
        return $total;
    }
}
?>
