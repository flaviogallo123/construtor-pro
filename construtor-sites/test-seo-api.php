<?php
session_start();
$_SESSION['user_id'] = 2; // Seu ID de usuário
$_SESSION['user_name'] = 'Test User';

// Incluir diretamente em vez de fazer HTTP request
require_once __DIR__ . '/config/database.php';

// Testar GET diretamente
$pageId = 2;
$stmt = $pdo->prepare('
    SELECT meta_title, meta_description, meta_keywords, og_title, og_description, og_image
    FROM pages WHERE id = ?
');
$stmt->execute([$pageId]);
$seo = $stmt->fetch();

echo "<h2>GET Response (dados atuais):</h2>";
echo "<pre>";
print_r($seo);
echo "</pre>";

// Testar UPDATE diretamente
echo "<h2>Testando UPDATE:</h2>";
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

$result = $stmt->execute([
    'Teste SEO Title',
    'Descrição de teste para SEO',
    'teste, seo, api',
    'Teste Open Graph',
    'Descrição OG de teste',
    'https://example.com/image.jpg',
    $pageId
]);

if ($result) {
    echo "<p style='color: green;'>✅ SEO atualizado com sucesso!</p>";
    
    // Verificar se atualizou
    $stmt = $pdo->prepare('SELECT * FROM pages WHERE id = ?');
    $stmt->execute([$pageId]);
    $updated = $stmt->fetch();
    
    echo "<h3>Dados atualizados:</h3>";
    echo "<pre>";
    print_r($updated);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>❌ Erro ao atualizar</p>";
}