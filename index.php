<?php

require_once "connection.php";

// GET users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
	try {
		$stmt = $conn->query('SELECT * FROM users');
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
		header('Content-Type: application/json');
		echo json_encode($users);
	} catch (PDOException $e) {
		echo json_encode(['error' => $e->getMessage()]);
	}
}
