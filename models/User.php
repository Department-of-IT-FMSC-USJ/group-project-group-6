<?php
/**
 * User Model
 * Handles all user-related database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/utils.php';

class User {
    
    /**
     * Create a new user
     * @param array $data User data
     * @return int|false User ID or false on failure
     */
    public static function create($data) {
        $conn = getDBConnection();
        
        $username = sanitize($data['username']);
        $email = sanitize($data['email']);
        $password_hash = hashPassword($data['password']);
        $full_name = sanitize($data['full_name']);
        $user_type = sanitize($data['user_type']);
        $phone = sanitize($data['phone'] ?? '');
        $address = sanitize($data['address'] ?? '');
        $district = sanitize($data['district'] ?? '');
        
        $sql = "INSERT INTO users (username, email, password_hash, full_name, user_type, phone, address, district) 
                VALUES ('$username', '$email', '$password_hash', '$full_name', '$user_type', '$phone', '$address', '$district')";
        
        if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);
            closeDBConnection($conn);
            return $user_id;
        }
        
        closeDBConnection($conn);
        return false;
    }
    
    /**
     * Find user by email
     * @param string $email User email
     * @return array|null User data or null
     */
    public static function findByEmail($email) {
        $conn = getDBConnection();
        $email = sanitize($email);
        
        $sql = "SELECT * FROM users WHERE email = '$email' AND is_active = 1";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            closeDBConnection($conn);
            return $user;
        }
        
        closeDBConnection($conn);
        return null;
    }
    
    /**
     * Find user by username
     * @param string $username Username
     * @return array|null User data or null
     */
    public static function findByUsername($username) {
        $conn = getDBConnection();
        $username = sanitize($username);
        
        $sql = "SELECT * FROM users WHERE username = '$username' AND is_active = 1";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            closeDBConnection($conn);
            return $user;
        }
        
        closeDBConnection($conn);
        return null;
    }
    
    /**
     * Find user by ID
     * @param int $id User ID
     * @return array|null User data or null
     */
    public static function findById($id) {
        $conn = getDBConnection();
        
        $sql = "SELECT * FROM users WHERE id = $id AND is_active = 1";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            closeDBConnection($conn);
            return $user;
        }
        
        closeDBConnection($conn);
        return null;
    }
    
    /**
     * Update user profile
     * @param int $id User ID
     * @param array $data Updated data
     * @return bool Success status
     */
    public static function update($id, $data) {
        $conn = getDBConnection();
        
        $full_name = sanitize($data['full_name'] ?? '');
        $phone = sanitize($data['phone'] ?? '');
        $address = sanitize($data['address'] ?? '');
        $district = sanitize($data['district'] ?? '');
        
        $sql = "UPDATE users SET 
                full_name = '$full_name',
                phone = '$phone',
                address = '$address',
                district = '$district',
                updated_at = CURRENT_TIMESTAMP
                WHERE id = $id";
        
        $result = mysqli_query($conn, $sql);
        closeDBConnection($conn);
        
        return $result;
    }
    
    /**
     * Get all users by type
     * @param string $type User type
     * @return array List of users
     */
    public static function getAllByType($type) {
        $conn = getDBConnection();
        $type = sanitize($type);
        
        $sql = "SELECT * FROM users WHERE user_type = '$type' AND is_active = 1 ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        $users = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }
        
        closeDBConnection($conn);
        return $users;
    }
    
    /**
     * Check if email exists
     * @param string $email Email to check
     * @return bool
     */
    public static function emailExists($email) {
        $conn = getDBConnection();
        $email = sanitize($email);
        
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        
        $exists = $result && mysqli_num_rows($result) > 0;
        closeDBConnection($conn);
        
        return $exists;
    }
    
    /**
     * Check if username exists
     * @param string $username Username to check
     * @return bool
     */
    public static function usernameExists($username) {
        $conn = getDBConnection();
        $username = sanitize($username);
        
        $sql = "SELECT id FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        
        $exists = $result && mysqli_num_rows($result) > 0;
        closeDBConnection($conn);
        
        return $exists;
    }
}
?>
