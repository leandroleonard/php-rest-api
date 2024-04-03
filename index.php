<?php

require_once "connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");

// GET users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && empty($_GET)) {
	try {
		$stmt = $conn->query('SELECT * FROM users');
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

		http_response_code(200);
		header('Content-Type: application/json');
		echo json_encode($users);
	} catch (PDOException $e) {
		http_response_code(500);
		exit(json_encode(['error' => $e->getMessage()]));
	}
}

// HTTP METHOD: POST
// Body Param: name, email
// Add new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);

	if (empty($data['name']) || empty($data['email'])) {
		http_response_code(400);
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
		http_response_code(201);
		echo json_encode(['id' => $userID, 'name' => $name, 'email' => $email]);
	} catch (PDOException $e) {
		http_response_code(500);
		exit(json_encode(['error' => $e->getMessage()]));
	}


	// HTTP METHOD: PUT
	// Update user
	if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
		$data = json_decode(file_get_contents('php://input'), true);

		if (empty($data['id']) || empty($data['name']) || empty($data['email'])) {
			http_response_code(400);
			echo json_encode(['error' => 'User data is required']);
			exit;
		}

		$userID = $data['id'];

		try {
			$stmt = $conn->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
			$stmt->bindParam(':id', $userID);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			http_response_code(201);
			echo json_encode(['success' => true]);
		} catch (PDOException $e) {
			http_response_code(500);
			exit(json_encode(['error' => $e->getMessage()]));
		}
	}

	// Delete user
	if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
		$data = json_decode(file_get_contents('php://input'), true);

		if (empty($data['id'])) {
			http_response_code(400);
			echo json_encode(['error' => 'UserID is required']);
			exit;
		}

		$userID = $data['id'];

		try {
			$stmt = $conn->prepare('DELETE FROM users WHERE id = :id');
			$stmt->bindParam(':id', $userID);
			$stmt->execute();

			http_response_code(204);
			echo json_encode(['success' => true]);
		} catch (PDOException $e) {
			http_response_code(500);
			exit(json_encode(['error' => $e->getMessage()]));
		}
	}

	http_response_code(400);
	exit(json_encode(['error' => '400 - Bad Request']));
}
