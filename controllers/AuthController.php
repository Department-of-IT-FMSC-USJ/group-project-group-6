<?php
/**
 * Authentication Controller
 * Handles user registration, login, and logout
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/session.php';
require_once __DIR__ . '/../helpers/utils.php';

class AuthController {
    
    /**
     * Handle user registration
     */
    public static function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        // Get form data
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $user_type = $_POST['user_type'] ?? 'household';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $district = $_POST['district'] ?? '';
        
        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
            setFlashMessage('All required fields must be filled!', 'error');
            redirect('/views/register.php');
            return;
        }
        
        if (!isValidEmail($email)) {
            setFlashMessage('Invalid email address!', 'error');
            redirect('/views/register.php');
            return;
        }
        
        if ($password !== $confirm_password) {
            setFlashMessage('Passwords do not match!', 'error');
            redirect('/views/register.php');
            return;
        }
        
        if (strlen($password) < 6) {
            setFlashMessage('Password must be at least 6 characters!', 'error');
            redirect('/views/register.php');
            return;
        }
        
        // Check if email or username already exists
        if (User::emailExists($email)) {
            setFlashMessage('Email already registered!', 'error');
            redirect('/views/register.php');
            return;
        }
        
        if (User::usernameExists($username)) {
            setFlashMessage('Username already taken!', 'error');
            redirect('/views/register.php');
            return;
        }
        
        // Create user
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'full_name' => $full_name,
            'user_type' => $user_type,
            'phone' => $phone,
            'address' => $address,
            'district' => $district
        ];
        
        $userId = User::create($userData);
        
        if ($userId) {
            setFlashMessage('Registration successful! Please login.', 'success');
            redirect('/views/login.php');
        } else {
            setFlashMessage('Registration failed. Please try again.', 'error');
            redirect('/views/register.php');
        }
    }
    
    /**
     * Handle user login
     */
    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate input
        if (empty($email) || empty($password)) {
            setFlashMessage('Email and password are required!', 'error');
            redirect('/views/login.php');
            return;
        }
        
        // Find user by email
        $user = User::findByEmail($email);
        
        if (!$user) {
            setFlashMessage('Invalid email or password!', 'error');
            redirect('/views/login.php');
            return;
        }
        
        // Verify password
        if (!verifyPassword($password, $user['password_hash'])) {
            setFlashMessage('Invalid email or password!', 'error');
            redirect('/views/login.php');
            return;
        }
        
        // Set session
        setUserSession($user);
        
        // Redirect based on user type
        if ($user['user_type'] === 'company') {
            redirect('/views/center-dashboard.php');
        } else {
            redirect('/views/dashboard.php');
        }
    }
    
    /**
     * Handle user logout
     */
    public static function logout() {
        destroyUserSession();
        setFlashMessage('You have been logged out successfully.', 'success');
        redirect('/views/index.php');
    }
    
    /**
     * Show login page
     */
    public static function showLogin() {
        redirectIfLoggedIn();
        require __DIR__ . '/../views/login.php';
    }
    
    /**
     * Show registration page
     */
    public static function showRegister() {
        redirectIfLoggedIn();
        require __DIR__ . '/../views/register.php';
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'login':
            AuthController::login();
            break;
        case 'register':
            AuthController::register();
            break;
        case 'logout':
            AuthController::logout();
            break;
    }
}
?>
