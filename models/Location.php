<?php
/**
 * Location Model
 * Handles all location-related database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/utils.php';

class Location {
    
    /**
     * Create a new location
     * @param array $data Location data
     * @return int|false Location ID or false on failure
     */
    public static function create($data) {
        $conn = getDBConnection();
        
        $name = sanitize($data['name']);
        $type = sanitize($data['type']);
        $address = sanitize($data['address']);
        $district = sanitize($data['district'] ?? '');
        $phone = sanitize($data['phone'] ?? '');
        $hours = sanitize($data['hours'] ?? '');
        $description = sanitize($data['description'] ?? '');
        $latitude = floatval($data['latitude'] ?? 0);
        $longitude = floatval($data['longitude'] ?? 0);
        $user_id = intval($data['user_id'] ?? 0);  // Add user_id handling
        
        // Log error if no user_id provided
        if ($user_id === 0) {
            error_log("Warning: Location being created without user_id");
        }
        
        $sql = "INSERT INTO locations (user_id, name, type, address, district, phone, hours, description, latitude, longitude) 
                VALUES ($user_id, '$name', '$type', '$address', '$district', '$phone', '$hours', '$description', $latitude, $longitude)";
        
        if (mysqli_query($conn, $sql)) {
            $location_id = mysqli_insert_id($conn);
            closeDBConnection($conn);
            return $location_id;
        }
        
        closeDBConnection($conn);
        return false;
    }
    
    /**
     * Get all locations
     * @return array List of locations
     */
    public static function getAll() {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM locations ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        $locations = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $locations[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $locations;
    }
    
    /**
     * Get verified locations only
     * @return array List of verified locations
     */
    public static function getVerified() {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM locations WHERE verified = 1 ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        $locations = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $locations[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $locations;
    }
    
    /**
     * Find location by ID
     * @param int $id Location ID
     * @return array|null Location data or null
     */
    public static function findById($id) {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM locations WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $location = mysqli_fetch_assoc($result);
            closeDBConnection($conn);
            return $location;
        }
        
        closeDBConnection($conn);
        return null;
    }
    
    /**
     * Search locations by district and/or type
     * @param string $district District name
     * @param string $type Location type
     * @return array List of locations
     */
    public static function search($district = '', $type = '') {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM locations WHERE verified = 1";
        
        if (!empty($district)) {
            $district = sanitize($district);
            $sql .= " AND district = '$district'";
        }
        
        if (!empty($type)) {
            $type = sanitize($type);
            $sql .= " AND type = '$type'";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $result = mysqli_query($conn, $sql);
        
        $locations = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $locations[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $locations;
    }
    
    /**
     * Update location
     * @param int $id Location ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public static function update($id, $data) {
        $conn = getDBConnection();
        
        $name = sanitize($data['name']);
        $type = sanitize($data['type']);
        $address = sanitize($data['address']);
        $district = sanitize($data['district'] ?? '');
        $phone = sanitize($data['phone'] ?? '');
        $hours = sanitize($data['hours'] ?? '');
        $description = sanitize($data['description'] ?? '');
        
        $sql = "UPDATE locations SET 
                name = '$name',
                type = '$type',
                address = '$address',
                district = '$district',
                phone = '$phone',
                hours = '$hours',
                description = '$description',
                updated_at = CURRENT_TIMESTAMP
                WHERE id = $id";
        
        $result = mysqli_query($conn, $sql);
        closeDBConnection($conn);
        
        return $result;
    }
    
    /**
     * Verify location
     * @param int $id Location ID
     * @return bool Success status
     */
    public static function verify($id) {
        $conn = getDBConnection();
        
        $sql = "UPDATE locations SET verified = 1, updated_at = CURRENT_TIMESTAMP WHERE id = $id";
        
        $result = mysqli_query($conn, $sql);
        closeDBConnection($conn);
        
        return $result;
    }
    
    /**
     * Delete location
     * @param int $id Location ID
     * @return bool Success status
     */
    public static function delete($id) {
        $conn = getDBConnection();
        
        $sql = "DELETE FROM locations WHERE id = $id";
        
        $result = mysqli_query($conn, $sql);
        closeDBConnection($conn);
        
        return $result;
    }
}
?>
