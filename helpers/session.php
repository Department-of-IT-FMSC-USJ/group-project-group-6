<?php
/**
 * Session Helper
 * Manages user sessions and authentication
 */

/**
 * Start session if not already started
 */
function initSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    initSession();
    return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

/**
 * Get current user ID
 * @return int|null
 */
function getCurrentUserId() {
    initSession();
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user type
 * @return string|null
 */
function getCurrentUserType() {
    initSession();
    return $_SESSION['user_type'] ?? null;
}

/**
 * Get current user data
 * @return array|null
 */
function getCurrentUser() {
    initSession();
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'full_name' => $_SESSION['full_name'] ?? '',
        'user_type' => $_SESSION['user_type']
    ];
}

/**
 * Set user session after login
 * @param array $userData User data from database
 */
function setUserSession($userData) {
    initSession();
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['email'] = $userData['email'];
    $_SESSION['full_name'] = $userData['full_name'];
    $_SESSION['user_type'] = $userData['user_type'];
}

/**
 * Destroy user session (logout)
 */
function destroyUserSession() {
    initSession();
    session_unset();
    session_destroy();
}

/**
 * Redirect if not logged in
 * @param string $redirectUrl URL to redirect to if not logged in
 */
function requireLogin($redirectUrl = '/views/login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectUrl");
        exit();
    }
}

/**
 * Redirect if already logged in
 * @param string $redirectUrl URL to redirect to if logged in
 */
function redirectIfLoggedIn($redirectUrl = '/views/dashboard.php') {
    if (isLoggedIn()) {
        header("Location: $redirectUrl");
        exit();
    }
}

/**
 * Check if user has specific role
 * @param string $role Role to check
 * @return bool
 */
function hasRole($role) {
    return isLoggedIn() && getCurrentUserType() === $role;
}

/**
 * Require specific role, redirect if not authorized
 * @param string $role Required role
 * @param string $redirectUrl URL to redirect to if not authorized
 */
function requireRole($role, $redirectUrl = '/views/dashboard.php') {
    if (!hasRole($role)) {
        header("Location: $redirectUrl");
        exit();
    }
}
?>
