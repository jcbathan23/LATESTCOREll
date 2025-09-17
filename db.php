<?php
// Simple DB connector for CORE II
// Updated to use constants and a connection helper while retaining backward compatibility

// Database configuration - UPDATE THESE FOR PRODUCTION
define('DB_HOST', 'localhost');
define('DB_NAME', 'core2');  // Change to your actual database name
define('DB_USER', 'root');   // Change to your actual username
define('DB_PASS', '');       // Change to your actual password

/**
 * Establishes a connection to the database.
 * @return mysqli|null The mysqli connection object on success, or null on failure.
 */
function getDbConnection() {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($conn->connect_error) {
		error_log('Database Connection Failed: ' . $conn->connect_error);
		die("Sorry, we're having some technical difficulties. Please try again later.");
	}
	return $conn;
}

// Backward compatibility: preserve $db and $mysqli used across the codebase
$db = getDbConnection();
$mysqli = getDbConnection();

// Minimal helpers used by API endpoints
if (!function_exists('send_json')) {
	function send_json($data, int $status = 200): void {
		header('Content-Type: application/json');
		http_response_code($status);
		echo json_encode($data);
		exit;
	}
}

if (!function_exists('read_json_body')) {
	function read_json_body(): array {
		$raw = file_get_contents('php://input');
		if ($raw === false || $raw === '') {
			return [];
		}
		$decoded = json_decode($raw, true);
		return is_array($decoded) ? $decoded : [];
	}
}

?>

