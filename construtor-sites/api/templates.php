<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/templates.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    // Listar templates disponíveis
    if ($method === 'GET') {
        $templates = getTemplates();
        $list = [];
        
        foreach ($templates as $key => $template) {
            $list[] = [
                'id' => $key,
                'name' => $template['name'],
                'description' => $template['description'],
                'icon' => $template['icon'],
                'color' => $template['color']
            ];
        }
        
        echo json_encode(['success' => true, 'templates' => $list]);
        exit;
    }
    
    // Obter elementos de um template específico
    if ($method === 'GET' && isset($_GET['template_id'])) {
        $templateId = $_GET['template_id'];
        $templates = getTemplates();
        
        if (!isset($templates[$templateId])) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Template não encontrado']);
            exit;
        }
        
        echo json_encode([
            'success' => true, 
            'template' => $templates[$templateId]
        ]);
        exit;
    }
    
    // Aplicar template a uma página
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['template_id']) || !isset($input['page_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
            exit;
        }
        
        $templateId = $input['template_id'];
        $pageId = (int)$input['page_id'];
        
        $templates = getTemplates();
        
        if (!isset($templates[$templateId])) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Template não encontrado']);
            exit;
        }
        
        $template = $templates[$templateId];
        
        // Deletar elementos existentes
        $stmt = $pdo->prepare('DELETE FROM elements WHERE page_id = ?');
        $stmt->execute([$pageId]);
        
        // Inserir elementos do template
        $stmt = $pdo->prepare('
            INSERT INTO elements (page_id, type, content, pos_x, pos_y, width, height, styles)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        
        foreach ($template['elements'] as $element) {
            $content = $element['content'] ?? '';
            
            // Se for social, salvar dados extras no content como JSON
            if ($element['type'] === 'social') {
                $content = json_encode([
                    'facebook' => $element['facebook'] ?? '#',
                    'instagram' => $element['instagram'] ?? '#',
                    'twitter' => $element['twitter'] ?? '#',
                    'linkedin' => $element['linkedin'] ?? '#'
                ]);
            }
            
            $stmt->execute([
                $pageId,
                $element['type'],
                $content,
                $element['x'] ?? 0,
                $element['y'] ?? 0,
                $element['width'] ?? 100,
                $element['height'] ?? 50,
                json_encode($element['styles'] ?? new stdClass())
            ]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Template aplicado!']);
        exit;
    }
    
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Endpoint não encontrado']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}