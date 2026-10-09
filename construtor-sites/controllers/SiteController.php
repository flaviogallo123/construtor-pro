<?php
class SiteController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Método para listar todos os sites do usuário
    public function getAll($userId) {
        try {
            $stmt = $this->pdo->prepare('
                SELECT s.*, 
                       COUNT(DISTINCT p.id) as pages_count
                FROM sites s
                LEFT JOIN pages p ON s.id = p.site_id
                WHERE s.user_id = ?
                GROUP BY s.id
                ORDER BY s.created_at DESC
            ');
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Erro no getAll: ' . $e->getMessage());
            return [];
        }
    }
    
    // Método para buscar site por ID
    public function getById($siteId) {
        $stmt = $this->pdo->prepare('SELECT * FROM sites WHERE id = ?');
        $stmt->execute([$siteId]);
        return $stmt->fetch();
    }
    
    // Método para criar novo site
    public function create($userId, $name, $slug) {
        try {
            $stmt = $this->pdo->prepare('
                INSERT INTO sites (user_id, name, slug, is_published, created_at) 
                VALUES (?, ?, ?, 0, NOW())
            ');
            $stmt->execute([$userId, $name, $slug]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Método para deletar site
    public function delete($siteId, $userId) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM sites WHERE id = ? AND user_id = ?');
            $stmt->execute([$siteId, $userId]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Método para visualizar site publicado
    public function view($siteSlug, $pageSlug) {
        try {
            // Buscar site
            $stmt = $this->pdo->prepare('SELECT * FROM sites WHERE slug = ?');
            $stmt->execute([$siteSlug]);
            $site = $stmt->fetch();
            
            if (!$site) {
                http_response_code(404);
                echo "Site não encontrado: " . htmlspecialchars($siteSlug);
                return;
            }
            
            // Buscar página pelo slug
            $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE site_id = ? AND slug = ?');
            $stmt->execute([$site['id'], $pageSlug]);
            $page = $stmt->fetch();
            
            // Se não achar pelo slug, pegar a primeira página
            if (!$page) {
                $stmt = $this->pdo->prepare('SELECT * FROM pages WHERE site_id = ? ORDER BY id LIMIT 1');
                $stmt->execute([$site['id']]);
                $page = $stmt->fetch();
            }
            
            if (!$page) {
                http_response_code(404);
                echo "Nenhuma página encontrada para este site<br>";
                echo "Site ID: " . $site['id'];
                return;
            }
            
            // Buscar elementos
            $stmt = $this->pdo->prepare('SELECT * FROM elements WHERE page_id = ? ORDER BY id ASC');
            $stmt->execute([$page['id']]);
            $elements = $stmt->fetchAll();
            
            // SEO Meta Tags
            $metaTitle = $page['meta_title'] ?? '';
            if (empty($metaTitle)) {
                $metaTitle = $site['name'] . ' - ' . $page['name'];
            }
            
            $metaDescription = $page['meta_description'] ?? '';
            if (empty($metaDescription)) {
                $metaDescription = $site['name'];
            }
            
            $metaKeywords = $page['meta_keywords'] ?? '';
            $ogTitle = $page['og_title'] ?? $metaTitle;
            $ogDescription = $page['og_description'] ?? $metaDescription;
            $ogImage = $page['og_image'] ?? '';
            
            // URL do site
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $siteUrl = $protocol . '://' . $host . '/construtor-sites/' . $site['slug'];
            
            // Renderizar página com SEO
            echo "<!DOCTYPE html>
            <html lang='pt-BR'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>" . htmlspecialchars($metaTitle) . "</title>
                
                <!-- SEO Meta Tags -->
                <meta name='description' content='" . htmlspecialchars($metaDescription) . "'>
                <meta name='keywords' content='" . htmlspecialchars($metaKeywords) . "'>
                <meta name='author' content='" . htmlspecialchars($site['name']) . "'>
                <link rel='canonical' href='" . htmlspecialchars($siteUrl) . "'>
                
                <!-- Open Graph / Facebook -->
                <meta property='og:type' content='website'>
                <meta property='og:url' content='" . htmlspecialchars($siteUrl) . "'>
                <meta property='og:title' content='" . htmlspecialchars($ogTitle) . "'>
                <meta property='og:description' content='" . htmlspecialchars($ogDescription) . "'>";
            
            if (!empty($ogImage)) {
                echo "<meta property='og:image' content='" . htmlspecialchars($ogImage) . "'>";
            }
            
            echo "
                <!-- Twitter -->
                <meta property='twitter:card' content='summary_large_image'>
                <meta property='twitter:url' content='" . htmlspecialchars($siteUrl) . "'>
                <meta property='twitter:title' content='" . htmlspecialchars($ogTitle) . "'>
                <meta property='twitter:description' content='" . htmlspecialchars($ogDescription) . "'>";
            
            if (!empty($ogImage)) {
                echo "<meta property='twitter:image' content='" . htmlspecialchars($ogImage) . "'>";
            }
            
            echo "
                <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
                <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
                <style>
                    body { 
                        margin: 0; 
                        font-family: 'Inter', Arial, sans-serif; 
                        background: #f5f6f8; 
                        min-height: 100vh; 
                    }
                    .element { position: absolute; }
                    .element img { width: 100%; height: 100%; object-fit: cover; }
                    
                    /* Animações CSS */
                    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
                    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
                    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
                    @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
                    @keyframes zoomIn { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
                    @keyframes zoomOut { from { opacity: 0; transform: scale(1.5); } to { opacity: 1; transform: scale(1); } }
                    @keyframes bounceIn { 0% { opacity: 0; transform: scale(0.3); } 50% { opacity: 1; transform: scale(1.05); } 70% { transform: scale(0.9); } 100% { transform: scale(1); } }
                    @keyframes rotateIn { from { opacity: 0; transform: rotate(-180deg); } to { opacity: 1; transform: rotate(0); } }
                    @keyframes flipIn { from { opacity: 0; transform: perspective(400px) rotateY(90deg); } to { opacity: 1; transform: perspective(400px) rotateY(0); } }
                    @keyframes slideInUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
                    @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
                    @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }
                    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
                    @keyframes swing { 20% { transform: rotate(15deg); } 40% { transform: rotate(-10deg); } 60% { transform: rotate(5deg); } 80% { transform: rotate(-5deg); } 100% { transform: rotate(0); } }
                    @keyframes rubberBand { 0% { transform: scale(1); } 30% { transform: scale(1.25, 0.75); } 40% { transform: scale(0.75, 1.25); } 50% { transform: scale(1.15, 0.85); } 65% { transform: scale(0.95, 1.05); } 75% { transform: scale(1.05, 0.95); } 100% { transform: scale(1); } }
                    
                    .animated { animation-fill-mode: both; }
                </style>
            </head>
            <body>";
            
            if (empty($elements)) {
                echo "<div style='padding: 50px; text-align: center; color: #666;'>
                        <h1>" . htmlspecialchars($page['name']) . "</h1>
                        <p style='color: #f00;'>⚠️ Nenhum elemento encontrado!</p>
                        <p>Página ID: {$page['id']}</p>
                        <p><a href='/construtor-sites/editor?page={$page['id']}&site={$site['id']}' style='color: #667eea;'>Clique aqui para editar</a></p>
                      </div>";
            } else {
                foreach ($elements as $element) {
                    $style = "position:absolute; left:{$element['pos_x']}px; top:{$element['pos_y']}px; width:{$element['width']}px; height:{$element['height']}px;";
                    
                    // Aplicar animação se houver
                    $styles = json_decode($element['styles'] ?? '{}', true);
                    if (!empty($styles['animation'])) {
                        $animation = $styles['animation'];
                        $duration = $styles['duration'] ?? '1';
                        $delay = $styles['delay'] ?? '0';
                        $repeat = $styles['repeat'] ?? '1';
                        $style .= " animation: {$animation} {$duration}s ease-out {$delay}s {$repeat};";
                    }
                    
                    $animatedClass = !empty($styles['animation']) ? ' animated' : '';
                    
                    echo "<div class='element{$animatedClass}' style='{$style}'>";
                    
                    switch ($element['type']) {
                        case 'text':
                            echo "<div style='width:100%; height:100%; font-size: 16px;'>" . htmlspecialchars($element['content']) . "</div>";
                            break;
                        case 'image':
                            echo "<img src='" . htmlspecialchars($element['content']) . "' alt='" . htmlspecialchars($metaTitle) . "'>";
                            break;
                        case 'button':
                            echo "<button style='width:100%; height:100%; padding: 10px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600;'>" . htmlspecialchars($element['content']) . "</button>";
                            break;
                        case 'video':
                            $videoId = $this->extractVideoId($element['content']);
                            if ($videoId) {
                                echo "<div style='width:100%; height:100%; background: #000; border-radius: 8px; overflow: hidden;'><iframe src='https://www.youtube.com/embed/{$videoId}' style='width:100%; height:100%; border: none;' allowfullscreen></iframe></div>";
                            }
                            break;
                        case 'form':
                            echo "<div style='width:100%; height:100%; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);'>
                                    <h4 style='margin-bottom: 15px; color: #212529; font-family: Poppins, sans-serif;'>Contato</h4>
                                    <input type='text' placeholder='Nome' style='width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e9ecef; border-radius: 8px;'>
                                    <input type='email' placeholder='Email' style='width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e9ecef; border-radius: 8px;'>
                                    <textarea placeholder='Mensagem' style='width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #e9ecef; border-radius: 8px; height: 80px;'></textarea>
                                    <button style='width: 100%; padding: 12px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;'>Enviar</button>
                                  </div>";
                            break;
                        case 'map':
                            $address = $element['content'];
                            if (!empty($address)) {
                                echo "<div style='width:100%; height:100%; background: #e9ecef; border-radius: 8px; overflow: hidden;'><iframe src='https://maps.google.com/maps?q=" . urlencode($address) . "&output=embed' style='width:100%; height:100%; border: none;'></iframe></div>";
                            } else {
                                echo "<div style='width:100%; height:100%; background: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #868e96;'><i class='fas fa-map-marker-alt' style='font-size: 48px;'></i></div>";
                            }
                            break;
                        case 'social':
                            $socialData = json_decode($element['content'], true);
                            if ($socialData) {
                                echo "<div style='width:100%; height:100%; display: flex; gap: 15px; align-items: center; justify-content: center;'>";
                                foreach ($socialData as $network => $url) {
                                    if ($url && $url !== '#' && !empty($url)) {
                                        echo "<a href='" . htmlspecialchars($url) . "' target='_blank' style='color: #3b82f6; transition: transform 0.2s;'><i class='fab fa-{$network}' style='font-size: 28px;'></i></a>";
                                    }
                                }
                                echo "</div>";
                            }
                            break;
                        case 'divider':
                            echo "<hr style='width: 100%; border: none; border-top: 2px solid #3b82f6; margin: 0;'>";
                            break;
                        default:
                            echo htmlspecialchars($element['content']);
                    }
                    
                    echo "</div>";
                }
            }
            
            echo "</body></html>";
            
        } catch (Exception $e) {
            http_response_code(500);
            echo "Erro: " . $e->getMessage();
        }
    }
    
    // Método auxiliar para extrair ID do YouTube
    private function extractVideoId($url) {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/';
        preg_match($pattern, $url, $matches);
        return isset($matches[1]) ? $matches[1] : null;
    }
}