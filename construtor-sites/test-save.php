<?php
session_start();
require_once __DIR__ . '/config/database.php';

echo "<h1>Teste de Salvamento</h1>";
echo "<p>User ID: " . ($_SESSION['user_id'] ?? 'NÃO LOGADO') . "</p>";
echo "<p>User Name: " . ($_SESSION['user_name'] ?? 'NÃO LOGADO') . "</p>";

// Verificar/criar site
$userId = $_SESSION['user_id'] ?? 1;

$stmt = $pdo->prepare('SELECT * FROM sites WHERE user_id = ? LIMIT 1');
$stmt->execute([$userId]);
$site = $stmt->fetch();

if (!$site) {
    echo "<p style='color: orange;'>Criando site...</p>";
    $stmt = $pdo->prepare('
        INSERT INTO sites (user_id, name, slug, is_published, created_at) 
        VALUES (?, ?, ?, 1, NOW())
    ');
    $stmt->execute([$userId, 'Meu Primeiro Site', 'meu-site-' . time()]);
    $siteId = $pdo->lastInsertId();
    
    $stmt = $pdo->prepare('SELECT * FROM sites WHERE id = ?');
    $stmt->execute([$siteId]);
    $site = $stmt->fetch();
}

echo "<p>Site ID: {$site['id']}</p>";
echo "<p>Site Name: {$site['name']}</p>";

// Verificar/criar página
$stmt = $pdo->prepare('SELECT * FROM pages WHERE site_id = ? LIMIT 1');
$stmt->execute([$site['id']]);
$page = $stmt->fetch();

if (!$page) {
    echo "<p style='color: orange;'>Criando página...</p>";
    $stmt = $pdo->prepare('
        INSERT INTO pages (site_id, name, slug) 
        VALUES (?, "Página Inicial", "home")
    ');
    $stmt->execute([$site['id']]);
    $pageId = $pdo->lastInsertId();
    
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = ?');
    $stmt->execute([$pageId]);
    $page = $stmt->fetch();
}

echo "<p>Page ID: {$page['id']}</p>";
echo "<p>Page Name: {$page['name']}</p>";

// Verificar elementos
$stmt = $pdo->prepare('SELECT * FROM elements WHERE page_id = ?');
$stmt->execute([$page['id']]);
$elements = $stmt->fetchAll();

echo "<p>Elementos salvos: " . count($elements) . "</p>";

echo "<hr>";
echo "<a href='/construtor-sites/editor?page={$page['id']}'>Ir para Editor</a>";