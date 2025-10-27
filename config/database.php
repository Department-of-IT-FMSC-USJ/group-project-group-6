<?php
/**
 * Database Configuration File
 * Contains database connection settings and mysqli connection function
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecocollect_db');
define('DB_PORT', 3306);

/**
 * Get database connection
 * @return mysqli|false Database connection object or false on failure
 */
function getDBConnection() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Set charset to utf8
    mysqli_set_charset($conn, "utf8");
    
    return $conn;
}

/**
 * Close database connection
 * @param mysqli $conn Database connection object
 */
function closeDBConnection($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}
?>
