<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'];

try {
    // Listar mídias do usuário
    if ($method === 'GET') {
        $stmt = $pdo->prepare('
            SELECT id, filename, original_name, file_url, file_size, mime_type, width, height, alt_text, created_at
            FROM media
            WHERE user_id = ?
            ORDER BY created_at DESC
        ');
        $stmt->execute([$userId]);
        $media = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'media' => $media]);
        exit;
    }
    
    // Upload de imagem
    if ($method === 'POST' && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        
        // Validações
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'error' => 'Erro no upload']);
            exit;
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'error' => 'Tipo de arquivo não permitido']);
            exit;
        }
        
        if ($file['size'] > 10 * 1024 * 1024) { // 10MB
            echo json_encode(['success' => false, 'error' => 'Arquivo muito grande (máx 10MB)']);
            exit;
        }
        
        // Gerar nome único
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_') . '.' . $ext;
        $uploadDir = __DIR__ . '/../uploads/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filePath = $uploadDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            echo json_encode(['success' => false, 'error' => 'Erro ao salvar arquivo']);
            exit;
        }
        
        // Obter dimensões da imagem
        $imageInfo = getimagesize($filePath);
        $width = $imageInfo[0] ?? 0;
        $height = $imageInfo[1] ?? 0;
        
        // URL pública
        $fileUrl = '/construtor-sites/uploads/' . $filename;
        
        // Salvar no banco
        $stmt = $pdo->prepare('
            INSERT INTO media (user_id, filename, original_name, file_path, file_url, file_size, mime_type, width, height)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        
        $stmt->execute([
            $userId,
            $filename,
            $file['name'],
            $filePath,
            $fileUrl,
            $file['size'],
            $file['type'],
            $width,
            $height
        ]);
        
        $mediaId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'media_id' => $mediaId,
            'url' => $fileUrl,
            'width' => $width,
            'height' => $height
        ]);
        exit;
    }
    
    // Upload múltiplo
    if ($method === 'POST' && isset($_FILES['files'])) {
        $files = $_FILES['files'];
        $uploaded = [];
        $errors = [];
        
        foreach ($files['tmp_name'] as $index => $tmpName) {
            if ($files['error'][$index] !== UPLOAD_ERR_OK) {
                $errors[] = $files['name'][$index] . ': Erro no upload';
                continue;
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($files['type'][$index], $allowedTypes)) {
                $errors[] = $files['name'][$index] . ': Tipo não permitido';
                continue;
            }
            
            if ($files['size'][$index] > 10 * 1024 * 1024) {
                $errors[] = $files['name'][$index] . ': Arquivo muito grande';
                continue;
            }
            
            $ext = pathinfo($files['name'][$index], PATHINFO_EXTENSION);
            $filename = uniqid('img_') . '.' . $ext;
            $uploadDir = __DIR__ . '/../uploads/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $filePath = $uploadDir . $filename;
            
            if (!move_uploaded_file($tmpName, $filePath)) {
                $errors[] = $files['name'][$index] . ': Erro ao salvar';
                continue;
            }
            
            $imageInfo = getimagesize($filePath);
            $width = $imageInfo[0] ?? 0;
            $height = $imageInfo[1] ?? 0;
            $fileUrl = '/construtor-sites/uploads/' . $filename;
            
            $stmt = $pdo->prepare('
                INSERT INTO media (user_id, filename, original_name, file_path, file_url, file_size, mime_type, width, height)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            
            $stmt->execute([
                $userId,
                $filename,
                $files['name'][$index],
                $filePath,
                $fileUrl,
                $files['size'][$index],
                $files['type'][$index],
                $width,
                $height
            ]);
            
            $uploaded[] = [
                'id' => $pdo->lastInsertId(),
                'url' => $fileUrl,
                'name' => $files['name'][$index]
            ];
        }
        
        echo json_encode([
            'success' => true,
            'uploaded' => $uploaded,
            'errors' => $errors
        ]);
        exit;
    }
    
    // Deletar mídia
    if ($method === 'DELETE' && isset($_GET['id'])) {
        $mediaId = (int)$_GET['id'];
        
        $stmt = $pdo->prepare('SELECT file_path FROM media WHERE id = ? AND user_id = ?');
        $stmt->execute([$mediaId, $userId]);
        $media = $stmt->fetch();
        
        if (!$media) {
            echo json_encode(['success' => false, 'error' => 'Mídia não encontrada']);
            exit;
        }
        
        // Deletar arquivo físico
        if (file_exists($media['file_path'])) {
            unlink($media['file_path']);
        }
        
        // Deletar do banco
        $stmt = $pdo->prepare('DELETE FROM media WHERE id = ? AND user_id = ?');
        $stmt->execute([$mediaId, $userId]);
        
        echo json_encode(['success' => true, 'message' => 'Mídia deletada!']);
        exit;
    }
    
    echo json_encode(['success' => false, 'error' => 'Endpoint não encontrado']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}