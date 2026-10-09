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

if ($method !== 'POST' || !isset($input['site_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit;
}

$siteId = (int)$input['site_id'];
$userId = $_SESSION['user_id'];

try {
    // Verificar se o site pertence ao usuário
    $stmt = $pdo->prepare('SELECT * FROM sites WHERE id = ? AND user_id = ?');
    $stmt->execute([$siteId, $userId]);
    $site = $stmt->fetch();
    
    if (!$site) {
        throw new Exception('Site não encontrado');
    }
    
    // Buscar todas as páginas
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE site_id = ? ORDER BY id ASC');
    $stmt->execute([$siteId]);
    $pages = $stmt->fetchAll();
    
    if (empty($pages)) {
        throw new Exception('Site sem páginas');
    }
    
    // Nome do ZIP
    $zipName = preg_replace('/[^a-z0-9]+/', '-', strtolower($site['name'])) . '.zip';
    $downloadsDir = __DIR__ . '/../downloads';
    
    // Criar pasta downloads se não existir
    if (!is_dir($downloadsDir)) {
        mkdir($downloadsDir, 0777, true);
    }
    
    $zipPath = $downloadsDir . '/' . $zipName;
    
    // Deletar ZIP antigo se existir
    if (file_exists($zipPath)) {
        unlink($zipPath);
    }
    
    // Criar ZIP
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
        throw new Exception('Não foi possível criar o ZIP');
    }
    
    // Adicionar CSS
    $css = generateCSS();
    $zip->addFromString('assets/style.css', $css);
    
    // Gerar HTML para cada página
    foreach ($pages as $index => $page) {
        $stmt = $pdo->prepare('SELECT * FROM elements WHERE page_id = ? ORDER BY id ASC');
        $stmt->execute([$page['id']]);
        $elements = $stmt->fetchAll();
        
        $html = generateHTML($site, $page, $elements, $pages);
        
        $filename = $index === 0 ? 'index.html' : ($page['slug'] ?: 'page-' . $page['id']) . '.html';
        $zip->addFromString($filename, $html);
    }
    
    // Adicionar sitemap.xml
    $sitemap = generateSitemap($site, $pages);
    $zip->addFromString('sitemap.xml', $sitemap);
    
    // Adicionar robots.txt
    $zip->addFromString('robots.txt', "User-agent: *\nAllow: /\nSitemap: sitemap.xml");
    
    $zip->close();
    
    // Retornar download direto
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipName . '"');
    header('Content-Length: ' . filesize($zipPath));
    readfile($zipPath);
    
    // Deletar ZIP após download
    unlink($zipPath);
    exit;
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// ============================================
// FUNÇÕES AUXILIARES
// ============================================

function generateCSS() {
    return '/* Estilo gerado automaticamente */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
    background: #f5f6f8;
    min-height: 100vh;
    overflow-x: hidden;
}

.page-container {
    position: relative;
    width: 100%;
    min-height: 100vh;
    max-width: 100vw;
    overflow: hidden;
    padding-top: 60px;
}

.element {
    position: absolute;
}

.element img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.element button {
    width: 100%;
    height: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    font-family: inherit;
}

.element iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.element .form-container {
    width: 100%;
    height: 100%;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.element .form-container h4 {
    margin-bottom: 15px;
    color: #212529;
}

.element .form-container input,
.element .form-container textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
}

.element .form-container textarea {
    height: 80px;
    resize: vertical;
}

.element .form-container button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.element .social-container {
    width: 100%;
    height: 100%;
    display: flex;
    gap: 15px;
    align-items: center;
    justify-content: center;
}

.element .social-container a {
    color: #3b82f6;
    text-decoration: none;
    transition: transform 0.2s;
}

.element .social-container a:hover {
    transform: scale(1.2);
}

.element .social-container i {
    font-size: 28px;
}

.element hr {
    width: 100%;
    border: none;
    border-top: 2px solid #3b82f6;
    margin: 0;
}

nav {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    padding: 12px 24px;
    display: flex;
    gap: 16px;
    z-index: 1000;
    border-bottom: 1px solid #e5e7eb;
}

nav a {
    font-weight: 700;
    color: #3b82f6;
    text-decoration: none;
}

.animated {
    animation-fill-mode: both;
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
@keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
@keyframes zoomIn { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
@keyframes bounceIn { 0% { opacity: 0; transform: scale(0.3); } 50% { opacity: 1; transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); } }
@keyframes rotateIn { from { opacity: 0; transform: rotate(-180deg); } to { opacity: 1; transform: rotate(0); } }
@keyframes flipIn { from { opacity: 0; transform: perspective(400px) rotateY(90deg); } to { opacity: 1; transform: perspective(400px) rotateY(0); } }
@keyframes slideInUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes swing { 20% { transform: rotate(15deg); } 40% { transform: rotate(-10deg); } 60% { transform: rotate(5deg); } 80% { transform: rotate(-5deg); } 100% { transform: rotate(0); } }
@keyframes rubberBand { 0% { transform: scale(1); } 30% { transform: scale(1.25, 0.75); } 40% { transform: scale(0.75, 1.25); } 50% { transform: scale(1.15, 0.85); } 65% { transform: scale(0.95, 1.05); } 75% { transform: scale(1.05, 0.95); } 100% { transform: scale(1); } }

@media (max-width: 768px) {
    .element {
        transform: scale(0.8) !important;
        transform-origin: top left;
    }
}
';
}

function generateHTML($site, $page, $elements, $allPages) {
    $metaTitle = $page['meta_title'] ?: ($site['name'] . ' - ' . $page['name']);
    $metaDescription = $page['meta_description'] ?: $site['name'];
    
    // Menu de navegação
    $nav = '<nav>';
    $nav .= '<a href="index.html">' . htmlspecialchars($site['name']) . '</a>';
    foreach ($allPages as $p) {
        $filename = ($p['id'] === $allPages[0]['id']) ? 'index.html' : ($p['slug'] ?: 'page-' . $p['id']) . '.html';
        $active = $p['id'] === $page['id'] ? 'font-weight:700;color:#3b82f6;' : '';
        $nav .= '<a href="' . $filename . '" style="' . $active . '">' . htmlspecialchars($p['name']) . '</a>';
    }
    $nav .= '</nav>';
    
    $html = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($metaTitle) . '</title>
    <meta name="description" content="' . htmlspecialchars($metaDescription) . '">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    ' . $nav . '
    <div class="page-container">';
    
    foreach ($elements as $element) {
        $styles = json_decode($element['styles'] ?? '{}', true);
        $style = "position:absolute; left:{$element['pos_x']}px; top:{$element['pos_y']}px; width:{$element['width']}px; height:{$element['height']}px;";
        
        if (!empty($styles['animation'])) {
            $animation = $styles['animation'];
            $duration = $styles['duration'] ?? '1';
            $delay = $styles['delay'] ?? '0';
            $repeat = $styles['repeat'] ?? '1';
            $style .= " animation: {$animation} {$duration}s ease-out {$delay}s {$repeat};";
        }
        
        $html .= '<div class="element animated" style="' . $style . '">';
        
        switch ($element['type']) {
            case 'text':
                $html .= '<div style="width:100%;height:100%;font-size:16px;">' . nl2br(htmlspecialchars($element['content'])) . '</div>';
                break;
            case 'image':
                $html .= '<img src="' . htmlspecialchars($element['content']) . '" alt="imagem">';
                break;
            case 'button':
                $html .= '<button>' . htmlspecialchars($element['content']) . '</button>';
                break;
            case 'video':
                $videoId = extractVideoId($element['content']);
                if ($videoId) {
                    $html .= '<iframe src="https://www.youtube.com/embed/' . $videoId . '" allowfullscreen></iframe>';
                }
                break;
            case 'form':
                $title = 'Contato';
                if (strpos($element['content'], '{') === 0) {
                    $formData = json_decode($element['content'], true);
                    $title = $formData['title'] ?? 'Contato';
                }
                $html .= '<div class="form-container">
                    <h4>' . htmlspecialchars($title) . '</h4>
                    <input type="text" placeholder="Nome">
                    <input type="email" placeholder="Email">
                    <textarea placeholder="Mensagem"></textarea>
                    <button>Enviar</button>
                </div>';
                break;
            case 'map':
                $address = $element['content'];
                if (!empty($address)) {
                    $html .= '<iframe src="https://maps.google.com/maps?q=' . urlencode($address) . '&output=embed"></iframe>';
                }
                break;
            case 'social':
                $socialData = json_decode($element['content'], true);
                if ($socialData) {
                    $html .= '<div class="social-container">';
                    foreach ($socialData as $network => $url) {
                        if ($url && $url !== '#' && !empty($url)) {
                            $html .= '<a href="' . htmlspecialchars($url) . '" target="_blank"><i class="fab fa-' . $network . '"></i></a>';
                        }
                    }
                    $html .= '</div>';
                }
                break;
            case 'divider':
                $html .= '<hr>';
                break;
        }
        
        $html .= '</div>';
    }
    
    $html .= '    </div>
</body>
</html>';
    
    return $html;
}

function generateSitemap($site, $pages) {
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    
    foreach ($pages as $index => $page) {
        $loc = $index === 0 ? 'index.html' : ($page['slug'] ?: 'page-' . $page['id']) . '.html';
        $xml .= '
    <url>
        <loc>' . htmlspecialchars($loc) . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>weekly</changefreq>
        <priority>' . ($index === 0 ? '1.0' : '0.8') . '</priority>
    </url>';
    }
    
    $xml .= '
</urlset>';
    
    return $xml;
}

function extractVideoId($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/';
    preg_match($pattern, $url, $matches);
    return isset($matches[1]) ? $matches[1] : null;
}