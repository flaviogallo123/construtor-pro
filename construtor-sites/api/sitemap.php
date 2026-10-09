<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/xml');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Buscar todos os sites publicados
$stmt = $pdo->prepare('
    SELECT s.slug as site_slug, p.slug as page_slug, p.updated_at
    FROM sites s
    JOIN pages p ON s.id = p.site_id
    WHERE s.is_published = 1
    ORDER BY s.id, p.id
');
$stmt->execute();
$pages = $stmt->fetchAll();

foreach ($pages as $page) {
    echo '<url>';
    echo '<loc>https://seusite.com/' . htmlspecialchars($page['site_slug']) . '/' . htmlspecialchars($page['page_slug']) . '</loc>';
    echo '<lastmod>' . date('Y-m-d', strtotime($page['updated_at'] ?? 'now')) . '</lastmod>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}

echo '</urlset>';