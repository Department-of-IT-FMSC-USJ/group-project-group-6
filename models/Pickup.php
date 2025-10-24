<?php
/**
 * Pickup Model
 * Handles all pickup request related database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/utils.php';

class Pickup {
    
    /**
     * Create a new pickup request
     * @param array $data Pickup data
     * @return int|false Pickup ID or false on failure
     */
    public static function create($data) {
        $conn = getDBConnection();
        
        $user_id = intval($data['user_id']);
        $location_id = isset($data['location_id']) ? intval($data['location_id']) : NULL;
        
        // Get company_id from the location's owner
        $company_id = 0;
        
        if ($location_id) {
            // Get the company that owns this location
            $locQuery = mysqli_query($conn, "SELECT user_id FROM locations WHERE id = $location_id");
            if ($locQuery && mysqli_num_rows($locQuery) > 0) {
                $locRow = mysqli_fetch_assoc($locQuery);
                $company_id = intval($locRow['user_id']);
            }
        }
        
        // If location has no owner, assign to any company as fallback
        if ($company_id === 0) {
            $companyQuery = mysqli_query($conn, "SELECT id FROM users WHERE user_type = 'company' LIMIT 1");
            if ($companyQuery && mysqli_num_rows($companyQuery) > 0) {
                $companyRow = mysqli_fetch_assoc($companyQuery);
                $company_id = intval($companyRow['id']);
            }
        }
        
        // If no company found, return error
        if ($company_id === 0) {
            closeDBConnection($conn);
            return false;
        }
        
        $pickup_date = sanitize($data['pickup_date']);
        $pickup_time = sanitize($data['pickup_time'] ?? '00:00:00');
        $estimated_weight = floatval($data['estimated_weight'] ?? 0);
        $instructions = sanitize($data['special_instructions'] ?? $data['instructions'] ?? '');
        
        $location_id_value = $location_id ? $location_id : 'NULL';
        
        $sql = "INSERT INTO pickup_requests (user_id, company_id, location_id, pickup_date, pickup_time, estimated_weight, instructions) 
                VALUES ($user_id, $company_id, $location_id_value, '$pickup_date', '$pickup_time', $estimated_weight, '$instructions')";
        
        if (mysqli_query($conn, $sql)) {
            $pickup_id = mysqli_insert_id($conn);
            closeDBConnection($conn);
            return $pickup_id;
        }
        
        closeDBConnection($conn);
        return false;
    }
    
    /**
     * Get all pickup requests for a user
     * @param int $userId User ID
     * @return array List of pickup requests
     */
    public static function getByUser($userId) {
        $conn = getDBConnection();
        
        $sql = "SELECT pr.*, u.full_name as company_name 
                FROM pickup_requests pr
                LEFT JOIN users u ON pr.company_id = u.id
                WHERE pr.user_id = $userId
                ORDER BY pr.created_at DESC";
        
        $result = mysqli_query($conn, $sql);
        
        $pickups = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pickups[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $pickups;
    }
    
    /**
     * Get all pickup requests for a company
     * @param int $companyId Company ID
     * @return array List of pickup requests
     */
    public static function getByCompany($companyId) {
        $conn = getDBConnection();
        
        $sql = "SELECT pr.*, u.full_name as user_name, u.phone as user_phone, 
                       u.address as user_address, u.email as user_email 
                FROM pickup_requests pr
                LEFT JOIN users u ON pr.user_id = u.id
                WHERE pr.company_id = $companyId
                ORDER BY pr.created_at DESC";
        
        $result = mysqli_query($conn, $sql);
        
        $pickups = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pickups[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $pickups;
    }
    
    /**
     * Find pickup by ID
     * @param int $id Pickup ID
     * @return array|null Pickup data or null
     */
    public static function findById($id) {
        $conn = getDBConnection();
        
        $sql = "SELECT pr.*, u.full_name as company_name, cu.full_name as user_name, cu.phone as user_phone, cu.address as user_address 
                FROM pickup_requests pr
                LEFT JOIN users u ON pr.company_id = u.id
                LEFT JOIN users cu ON pr.user_id = cu.id
                WHERE pr.id = $id";
        
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $pickup = mysqli_fetch_assoc($result);
            closeDBConnection($conn);
            return $pickup;
        }
        
        closeDBConnection($conn);
        return null;
    }
    
    /**
     * Update pickup status
     * @param int $id Pickup ID
     * @param string $status New status
     * @return bool Success status
     */
    public static function updateStatus($id, $status) {
        $conn = getDBConnection();
        $status = sanitize($status);
        
        $sql = "UPDATE pickup_requests SET status = '$status', updated_at = CURRENT_TIMESTAMP WHERE id = $id";
        
        $result = mysqli_query($conn, $sql);
        closeDBConnection($conn);
        
        return $result;
    }
    
    /**
     * Delete pickup request
     * @param int $id Pickup ID
     * @return bool Success status
     */
    public static function delete($id) {
        $conn = getDBConnection();
        
        // First delete associated items
        $sql = "DELETE FROM pickup_items WHERE pickup_id = $id";
        mysqli_query($conn, $sql);
        
        // Then delete the pickup request
        $sql = "DELETE FROM pickup_requests WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        
        closeDBConnection($conn);
        return $result;
    }
    
    /**
     * Add pickup items
     * @param int $pickupId Pickup ID
     * @param array $items Array of items
     * @return bool Success status
     */
    public static function addItems($pickupId, $items) {
        $conn = getDBConnection();
        $success = true;
        
        foreach ($items as $item) {
            $plastic_type = sanitize($item['plastic_type']);
            $quantity = floatval($item['quantity']);
            
            $sql = "INSERT INTO pickup_items (pickup_id, plastic_type, quantity) 
                    VALUES ($pickupId, '$plastic_type', $quantity)";
            
            if (!mysqli_query($conn, $sql)) {
                $success = false;
                break;
            }
        }
        
        closeDBConnection($conn);
        return $success;
    }
    
    /**
     * Get pickup items
     * @param int $pickupId Pickup ID
     * @return array List of items
     */
    public static function getItems($pickupId) {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM pickup_items WHERE pickup_id = $pickupId";
        $result = mysqli_query($conn, $sql);
        
        $items = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $items;
    }
}
?>
