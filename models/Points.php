<?php
/**
 * Points Model
 * Handles all points-related database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/utils.php';

class Points {
    
    /**
     * Add points to a user
     * @param int $userId User ID
     * @param int $points Points to add
     * @param string $description Description of transaction
     * @return int|false Points ID or false on failure
     */
    public static function add($userId, $points, $description = '') {
        $conn = getDBConnection();
        
        $description = sanitize($description);
        
        $sql = "INSERT INTO points (user_id, points_earned, description) 
                VALUES ($userId, $points, '$description')";
        
        if (mysqli_query($conn, $sql)) {
            $points_id = mysqli_insert_id($conn);
            closeDBConnection($conn);
            return $points_id;
        }
        
        closeDBConnection($conn);
        return false;
    }
    
    /**
     * Use points from a user
     * @param int $userId User ID
     * @param int $points Points to use
     * @param string $description Description of transaction
     * @return int|false Points ID or false on failure
     */
    public static function use($userId, $points, $description = '') {
        $conn = getDBConnection();
        
        $description = sanitize($description);
        
        $sql = "INSERT INTO points (user_id, points_earned, points_used, description) 
                VALUES ($userId, 0, $points, '$description')";
        
        if (mysqli_query($conn, $sql)) {
            $points_id = mysqli_insert_id($conn);
            closeDBConnection($conn);
            return $points_id;
        }
        
        closeDBConnection($conn);
        return false;
    }
    
    /**
     * Get total balance for a user
     * @param int $userId User ID
     * @return int Total points balance
     */
    public static function getBalance($userId) {
        $conn = getDBConnection();
        
        $sql = "SELECT 
                COALESCE(SUM(points_earned), 0) - COALESCE(SUM(points_used), 0) as balance 
                FROM points 
                WHERE user_id = $userId";
        
        $result = mysqli_query($conn, $sql);
        
        $balance = 0;
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $balance = intval($row['balance']);
        }
        
        closeDBConnection($conn);
        return $balance;
    }
    
    /**
     * Get points history for a user
     * @param int $userId User ID
     * @param int $limit Limit number of results
     * @return array List of points transactions
     */
    public static function getHistory($userId, $limit = 50) {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM points WHERE user_id = $userId ORDER BY created_at DESC LIMIT $limit";
        
        $result = mysqli_query($conn, $sql);
        
        $history = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $history[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $history;
    }
    
    /**
     * Get total earned points for a user
     * @param int $userId User ID
     * @return int Total earned points
     */
    public static function getTotalEarned($userId) {
        $conn = getDBConnection();
        
        $sql = "SELECT COALESCE(SUM(points_earned), 0) as total FROM points WHERE user_id = $userId";
        
        $result = mysqli_query($conn, $sql);
        
        $total = 0;
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $total = intval($row['total']);
        }
        
        closeDBConnection($conn);
        return $total;
    }
    
    /**
     * Get total used points for a user
     * @param int $userId User ID
     * @return int Total used points
     */
    public static function getTotalUsed($userId) {
        $conn = getDBConnection();
        
        $sql = "SELECT COALESCE(SUM(points_used), 0) as total FROM points WHERE user_id = $userId";
        
        $result = mysqli_query($conn, $sql);
        
        $total = 0;
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $total = intval($row['total']);
        }
        
        closeDBConnection($conn);
        return $total;
    }
    
    /**
     * Get leaderboard (top users by points)
     * @param int $limit Number of users to return
     * @return array List of users with points
     */
    public static function getLeaderboard($limit = 10) {
        $conn = getDBConnection();
        
        $sql = "SELECT u.id, u.full_name, u.district,
                SUM(p.points_earned) - SUM(p.points_used) as total_points
                FROM users u
                LEFT JOIN points p ON u.id = p.user_id
                WHERE u.user_type IN ('household', 'business')
                GROUP BY u.id
                ORDER BY total_points DESC
                LIMIT $limit";
        
        $result = mysqli_query($conn, $sql);
        
        $leaderboard = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $leaderboard[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $leaderboard;
    }
}
?>
