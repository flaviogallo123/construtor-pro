<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/PageController.php';
require_once __DIR__ . '/controllers/SiteController.php';

// Router simples
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Remover prefixo "construtor-sites" se existir
if (strpos($uri, 'construtor-sites/') === 0) {
    $uri = substr($uri, strlen('construtor-sites/'));
}
if ($uri === 'construtor-sites') {
    $uri = '';
}

$method = $_SERVER['REQUEST_METHOD'];

// Rotas de API
if (strpos($uri, 'api/') === 0) {
    header('Content-Type: application/json');
    
    // Auth
    if ($uri === 'api/auth/register' && $method === 'POST') {
        $controller = new AuthController($pdo);
        $controller->register();
        exit;
    }
    
    if ($uri === 'api/auth/login' && $method === 'POST') {
        $controller = new AuthController($pdo);
        $controller->login();
        exit;
    }
    
    if ($uri === 'api/auth/logout' && ($method === 'POST' || $method === 'GET')) {
        $controller = new AuthController($pdo);
        $controller->logout();
        exit;
    }
    
    if ($uri === 'api/auth/check' && $method === 'GET') {
        $controller = new AuthController($pdo);
        $controller->check();
        exit;
    }
    
    // Pages
    if ($uri === 'api/pages' && $method === 'GET') {
        require_once __DIR__ . '/api/pages.php';
        exit;
    }
    
    if ($uri === 'api/pages' && $method === 'POST') {
        require_once __DIR__ . '/api/pages.php';
        exit;
    }
    
    if ($uri === 'api/pages' && $method === 'PUT') {
        require_once __DIR__ . '/api/pages.php';
        exit;
    }
    
    if (preg_match('/^api\/pages\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
        $_GET['page_id'] = $matches[1];
        require_once __DIR__ . '/api/pages.php';
        exit;
    }
    
    // Sites
    if ($uri === 'api/sites' && $method === 'GET') {
        require_once __DIR__ . '/api/sites.php';
        exit;
    }
    
    if ($uri === 'api/sites' && $method === 'POST') {
        require_once __DIR__ . '/api/sites.php';
        exit;
    }
    
    if (preg_match('/^api\/sites\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
        require_once __DIR__ . '/api/sites.php';
        exit;
    }
    
    // Stats
    if ($uri === 'api/stats' && $method === 'GET') {
        require_once __DIR__ . '/api/stats.php';
        exit;
    }
    
    // Media
    if ($uri === 'api/media' && $method === 'GET') {
        require_once __DIR__ . '/api/media.php';
        exit;
    }
    
    if ($uri === 'api/media' && $method === 'POST') {
        require_once __DIR__ . '/api/media.php';
        exit;
    }
    
    if (preg_match('/^api\/media\/(\d+)$/', $uri, $matches) && $method === 'DELETE') {
        require_once __DIR__ . '/api/media.php';
        exit;
    }
    
    // Upload simples
    if ($uri === 'api/upload-simple' && $method === 'POST') {
        require_once __DIR__ . '/api/upload-simple.php';
        exit;
    }
    
    // Save elements
    if ($uri === 'api/save-elements' && $method === 'POST') {
        $controller = new PageController($pdo);
        $controller->saveElements();
        exit;
    }
    
    // Get elements
    if (preg_match('/^api\/elements\/(\d+)$/', $uri, $matches) && $method === 'GET') {
        $controller = new PageController($pdo);
        $elements = $controller->getElements($matches[1]);
        echo json_encode($elements);
        exit;
    }
    
    // Templates
    if ($uri === 'api/templates' && $method === 'GET') {
        require_once __DIR__ . '/api/templates.php';
        exit;
    }
    
    if ($uri === 'api/templates' && $method === 'POST') {
        require_once __DIR__ . '/api/templates.php';
        exit;
    }
    
    if (preg_match('/^api\/templates\/(\w+)$/', $uri, $matches) && $method === 'GET') {
        $_GET['template_id'] = $matches[1];
        require_once __DIR__ . '/api/templates.php';
        exit;
    }
    
    // SEO
    if ($uri === 'api/seo' && ($method === 'GET' || $method === 'POST')) {
        require_once __DIR__ . '/api/seo.php';
        exit;
    }
    
    // Export - ROTA CORRIGIDA (AGORA ESTÁ DENTRO DO BLOCO API)
    if ($uri === 'api/export' && $method === 'POST') {
        require_once __DIR__ . '/api/export.php';
        exit;
    }
    
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint não encontrado: ' . $uri]);
    exit;
}

// Sitemap (fora da API, pois é XML)
if ($uri === 'sitemap.xml') {
    require_once __DIR__ . '/api/sitemap.php';
    exit;
}

// Rotas de páginas
if ($uri === '' || $uri === 'index.php') {
    if (isset($_SESSION['user_id'])) {
        header('Location: /construtor-sites/dashboard');
        exit;
    } else {
        include __DIR__ . '/views/landing_page.php';
        exit;
    }
}

if ($uri === 'login') {
    include __DIR__ . '/views/login.php';
    exit;
}

if ($uri === 'register') {
    include __DIR__ . '/views/register.php';
    exit;
}

if ($uri === 'dashboard') {
    include __DIR__ . '/views/dashboard.php';
    exit;
}

if ($uri === 'media-library' || $uri === 'media-library/') {
    include __DIR__ . '/views/media-library.php';
    exit;
}

if ($uri === 'editor' || $uri === 'editor/') {
    // Verificar se está logado
    if (!isset($_SESSION['user_id'])) {
        header('Location: /construtor-sites/login');
        exit;
    }
    
    $pageId = $_GET['page'] ?? 1;
    $controller = new PageController($pdo);
    $controller->editor($pageId);
    exit;
}

// Rota para visualizar site publicado
$pathParts = explode('/', $uri);
$siteSlug = $pathParts[0] ?? null;

if ($siteSlug && !in_array($siteSlug, ['login', 'register', 'dashboard', 'editor', 'media-library', 'api'])) {
    $stmt = $pdo->prepare('SELECT id FROM sites WHERE slug = ?');
    $stmt->execute([$siteSlug]);
    $siteExists = $stmt->fetch();
    
    if ($siteExists) {
        $pageSlug = $pathParts[1] ?? 'home';
        $controller = new SiteController($pdo);
        $controller->view($siteSlug, $pageSlug);
        exit;
    }
}

// 404
http_response_code(404);
echo "Página não encontrada: " . $uri;