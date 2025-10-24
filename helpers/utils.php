<?php
/**
 * Utility Helper Functions
 */

/**
 * Sanitize input data
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Redirect to a URL
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Set flash message
 * @param string $message Message text
 * @param string $type Message type (success, error, warning, info)
 */
function setFlashMessage($message, $type = 'info') {
    initSession();
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

/**
 * Get and clear flash message
 * @return array|null Array with 'message' and 'type' or null
 */
function getFlashMessage() {
    initSession();
    if (isset($_SESSION['flash_message'])) {
        $message = [
            'message' => $_SESSION['flash_message'],
            'type' => $_SESSION['flash_type']
        ];
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return $message;
    }
    return null;
}

/**
 * Format date for display
 * @param string $date Date string
 * @param string $format Desired format
 * @return string Formatted date
 */
function formatDate($date, $format = 'Y-m-d') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Format number with decimals
 * @param float $number Number to format
 * @param int $decimals Number of decimal places
 * @return string Formatted number
 */
function formatNumber($number, $decimals = 2) {
    return number_format($number, $decimals);
}

/**
 * Calculate points based on weight
 * @param float $weight Weight in kg
 * @return int Points earned
 */
function calculatePoints($weight) {
    // 5 points per kg
    return round($weight * 5);
}

/**
 * Get Sri Lankan districts
 * @return array List of districts
 */
function getDistricts() {
    return [
        'Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya',
        'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar',
        'Vavuniya', 'Mullaitivu', 'Batticaloa', 'Ampara', 'Trincomalee',
        'Kurunegala', 'Puttalam', 'Anuradhapura', 'Polonnaruwa', 'Badulla',
        'Monaragala', 'Ratnapura', 'Kegalle'
    ];
}

/**
 * Get plastic types
 * @return array List of plastic types
 */
function getPlasticTypes() {
    return ['PET', 'HDPE', 'PP', 'PS', 'Film', 'Mixed'];
}

/**
 * Get pickup statuses
 * @return array List of pickup statuses
 */
function getPickupStatuses() {
    return ['pending', 'accepted', 'assigned', 'in_progress', 'completed', 'canceled'];
}

/**
 * Validate email
 * @param string $email Email to validate
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password
 * @param string $password Plain password
 * @return string Hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 * @param string $password Plain password
 * @param string $hash Hashed password
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>
