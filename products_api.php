<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/connection.php';

$pdo = getPDO();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

try {
    if ($method === 'GET' && $action === 'list') {
        // List products
        $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
        $products = $stmt->fetchAll();
        echo json_encode(['success' => true, 'products' => $products]);
        exit;
    }

    // For write operations expect JSON body
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    if ($method === 'POST' && ($action === 'add' || $action === 'update')) {
        $name = $input['name'] ?? '';
        $category = $input['category'] ?? '';
        $state = $input['state'] ?? '';
        $quantity = intval($input['quantity'] ?? 0);
        $transit = intval($input['transit'] ?? 0);
        $price = floatval($input['price'] ?? 0);
        $description = $input['description'] ?? '';

        if ($action === 'add') {
            $stmt = $pdo->prepare('INSERT INTO products (name, category, state, quantity, transit, price, description) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $category, $state, $quantity, $transit, $price, $description]);
            $id = $pdo->lastInsertId();
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            echo json_encode(['success' => true, 'product' => $product]);
            exit;
        } else {
            $id = intval($input['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('Invalid id for update');
            }
            $stmt = $pdo->prepare('UPDATE products SET name=?, category=?, state=?, quantity=?, transit=?, price=?, description=? WHERE id=?');
            $stmt->execute([$name, $category, $state, $quantity, $transit, $price, $description, $id]);
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            echo json_encode(['success' => true, 'product' => $product]);
            exit;
        }
    }

    if ($method === 'POST' && $action === 'delete') {
        $id = intval($input['id'] ?? 0);
        if ($id <= 0) throw new Exception('Invalid id for delete');
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
