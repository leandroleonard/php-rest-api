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

// HTTP METHOD: POST
// Body Param: name, email
// Add new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);

	if (empty($data['name']) || empty($data['email'])) {
		echo json_encode(['error' => 'Username and email are required']);
		exit;
	}

	$name = $data['name'];
	$email = $data['email'];

	try {
		$stmt = $conn->prepare('INSERT INTO users (name, email) VALUES (:name, :email)');
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		$userID = $conn->lastInsertId();
		echo json_encode(['id' => $userID, 'name' => $name, 'email' => $email]);
	} catch (PDOException $e) {
		echo json_encode(['error' => $e->getMessage()]);
	}
}
