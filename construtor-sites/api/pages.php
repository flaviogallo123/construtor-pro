<?php
require_once __DIR__ . '/../config/database.php';

// Garantir que é JSON
header('Content-Type: application/json');

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    // Listar páginas de um site (GET)
    if ($method === 'GET') {
        $siteId = isset($_GET['site_id']) ? (int)$_GET['site_id'] : 0;
        
        if (!$siteId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'site_id obrigatório']);
            exit;
        }
        
        // Verificar se o site pertence ao usuário
        $stmt = $pdo->prepare('SELECT id FROM sites WHERE id = ? AND user_id = ?');
        $stmt->execute([$siteId, $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Site não encontrado']);
            exit;
        }
        
        $stmt = $pdo->prepare('
            SELECT p.*, 
                   (SELECT COUNT(*) FROM elements WHERE page_id = p.id) as elements_count
            FROM pages p
            WHERE p.site_id = ?
            ORDER BY p.id ASC
        ');
        $stmt->execute([$siteId]);
        $pages = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'pages' => $pages]);
        exit;
    }
    
    // Criar nova página (POST)
    if ($method === 'POST') {
        if (!isset($input['site_id']) || !isset($input['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
            exit;
        }
        
        $siteId = (int)$input['site_id'];
        $name = trim($input['name']);
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        
        // Verificar se o site pertence ao usuário
        $stmt = $pdo->prepare('SELECT id FROM sites WHERE id = ? AND user_id = ?');
        $stmt->execute([$siteId, $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Site não encontrado']);
            exit;
        }
        
        // Verificar se slug já existe
        $stmt = $pdo->prepare('SELECT id FROM pages WHERE site_id = ? AND slug = ?');
        $stmt->execute([$siteId, $slug]);
        if ($stmt->fetch()) {
            $slug .= '-' . time();
        }
        
        $stmt = $pdo->prepare('INSERT INTO pages (site_id, name, slug) VALUES (?, ?, ?)');
        $stmt->execute([$siteId, $name, $slug]);
        $pageId = $pdo->lastInsertId();
        
        echo json_encode(['success' => true, 'page_id' => $pageId, 'message' => 'Página criada!']);
        exit;
    }
    
    // Renomear página (PUT)
    if ($method === 'PUT') {
        if (!isset($input['page_id']) || !isset($input['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
            exit;
        }
        
        $pageId = (int)$input['page_id'];
        $name = trim($input['name']);
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        
        // Verificar se a página pertence ao usuário
        $stmt = $pdo->prepare('
            SELECT p.id FROM pages p
            JOIN sites s ON p.site_id = s.id
            WHERE p.id = ? AND s.user_id = ?
        ');
        $stmt->execute([$pageId, $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Página não encontrada']);
            exit;
        }
        
        $stmt = $pdo->prepare('UPDATE pages SET name = ?, slug = ? WHERE id = ?');
        $stmt->execute([$name, $slug, $pageId]);
        
        echo json_encode(['success' => true, 'message' => 'Página renomeada!']);
        exit;
    }
    
    // Deletar página (DELETE)
    if ($method === 'DELETE') {
        if (!isset($_GET['page_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'page_id obrigatório']);
            exit;
        }
        
        $pageId = (int)$_GET['page_id'];
        
        // Verificar se a página pertence ao usuário
        $stmt = $pdo->prepare('
            SELECT p.id FROM pages p
            JOIN sites s ON p.site_id = s.id
            WHERE p.id = ? AND s.user_id = ?
        ');
        $stmt->execute([$pageId, $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Página não encontrada']);
            exit;
        }
        
        // Deletar elementos primeiro
        $stmt = $pdo->prepare('DELETE FROM elements WHERE page_id = ?');
        $stmt->execute([$pageId]);
        
        // Deletar página
        $stmt = $pdo->prepare('DELETE FROM pages WHERE id = ?');
        $stmt->execute([$pageId]);
        
        echo json_encode(['success' => true, 'message' => 'Página deletada!']);
        exit;
    }
    
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}