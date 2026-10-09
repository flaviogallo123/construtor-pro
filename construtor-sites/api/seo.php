<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    // Salvar SEO
    if ($method === 'POST') {
        $pageId = isset($input['page_id']) ? (int)$input['page_id'] : 0;
        
        if ($pageId <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID da página inválido']);
            exit;
        }
        
        $stmt = $pdo->prepare('
            UPDATE pages SET 
                meta_title = ?,
                meta_description = ?,
                meta_keywords = ?,
                og_title = ?,
                og_description = ?,
                og_image = ?
            WHERE id = ?
        ');
        
        $stmt->execute([
            $input['meta_title'] ?? null,
            $input['meta_description'] ?? null,
            $input['meta_keywords'] ?? null,
            $input['og_title'] ?? null,
            $input['og_description'] ?? null,
            $input['og_image'] ?? null,
            $pageId
        ]);
        
        echo json_encode(['success' => true, 'message' => 'SEO salvo com sucesso!']);
        exit;
    }
    
    // Obter SEO
    if ($method === 'GET') {
        $pageId = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;
        
        $stmt = $pdo->prepare('
            SELECT meta_title, meta_description, meta_keywords, og_title, og_description, og_image
            FROM pages WHERE id = ?
        ');
        $stmt->execute([$pageId]);
        $seo = $stmt->fetch();
        
        echo json_encode(['success' => true, 'seo' => $seo]);
        exit;
    }
    
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}