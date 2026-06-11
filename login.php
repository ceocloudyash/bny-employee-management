<?php
// login.php
ob_start();
session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'domain' => '',
  'secure' => true,
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();

require_once 'db.php';
require_once 'helpers.php';

enforce_https();
send_hsts_header();

define('MAX_ATTEMPTS', 5);
define('LOCKOUT_MINUTES', 15);

function record_failed_attempt($conn, $username, $ip, $reason = null) {
    $stmt = $conn->prepare('INSERT INTO failed_logins (username, ip_address, reason) VALUES (?, ?, ?)');
    if ($stmt) {
        $stmt->bind_param('sss', $username, $ip, $reason);
        $stmt->execute();
        $stmt->close();
    }
}

function is_locked_out($conn, $username, $ip) {
    $since = date('Y-m-d H:i:s', time() - LOCKOUT_MINUTES * 60);
    $stmt = $conn->prepare('SELECT COUNT(*) AS cnt FROM failed_logins WHERE (username = ? OR ip_address = ?) AND attempt_time >= ?');
    if (!$stmt) return false;
    $stmt->bind_param('sss', $username, $ip, $since);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    return ($row['cnt'] ?? 0) >= MAX_ATTEMPTS;
}

function audit_event($conn, $user_id, $username, $ip, $event_type, $details = null) {
    $stmt = $conn->prepare('INSERT INTO auth_audit (user_id, username, ip_address, event_type, details) VALUES (?, ?, ?, ?, ?)');
    if ($stmt) {
        $stmt->bind_param('issss', $user_id, $username, $ip, $event_type, $details);
        $stmt->execute();
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$csrf = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrf)) {
    http_response_code(400);
    exit('Invalid request');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$ip = get_client_ip();

if ($username === '' || $password === '') {
    record_failed_attempt($conn, $username, $ip, 'missing_fields');
    http_response_code(400);
    include 'login_failed_view.php';
    exit();
}

if (strlen($username) > 100 || strlen($password) > 256) {
    record_failed_attempt($conn, $username, $ip, 'input_length');
    include 'login_failed_view.php';
    exit();
}

if (is_locked_out($conn, $username, $ip)) {
    audit_event($conn, null, $username, $ip, 'lockout_attempt', 'Too many failed attempts');
    include 'login_failed_view.php';
    exit();
}

$stmt = $conn->prepare('SELECT id, username, password_hash, role, display_name FROM users WHERE username = ? LIMIT 1');
if (!$stmt) {
    error_log('DB prepare failed: ' . $conn->error);
    include 'login_failed_view.php';
    exit();
}
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $storedHash = $row['password_hash'];

    if (password_verify($password, $storedHash)) {
        if (password_needs_rehash($storedHash, PASSWORD_DEFAULT)) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $upd = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            if ($upd) {
                $upd->bind_param('si', $newHash, $row['id']);
                $upd->execute();
                $upd->close();
            }
        }

        session_regenerate_id(true);
        // Clear any previous session data
        $_SESSION = [];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['display_name'] = $row['display_name'];
        $_SESSION['role'] = $row['role'];

        audit_event($conn, $row['id'], $row['username'], $ip, 'login_success', null);

        // Redirect based on role
        if ($row['role'] === 'CEO') {
            header('Location: dashboard.php');
        } else {
            header('Location: employee_dashboard.php');
        }

        $stmt->close();
        $conn->close();
        ob_end_flush();
        exit();
    }
}

// If we reach here, authentication failed
record_failed_attempt($conn, $username, $ip, 'invalid_credentials');
audit_event($conn, null, $username, $ip, 'login_failed', null);

$stmt->close();
$conn->close();

include 'login_failed_view.php';
exit();