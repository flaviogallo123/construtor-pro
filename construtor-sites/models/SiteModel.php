<?php
class SiteModel {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getAll($userId) {
        $stmt = $this->pdo->prepare('
            SELECT s.*, COUNT(DISTINCT p.id) as pages_count
            FROM sites s
            LEFT JOIN pages p ON s.id = p.site_id
            WHERE s.user_id = ?
            GROUP BY s.id
            ORDER BY s.updated_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getById($siteId) {
        $stmt = $this->pdo->prepare('SELECT * FROM sites WHERE id = ?');
        $stmt->execute([$siteId]);
        return $stmt->fetch();
    }
    
    public function getBySlug($slug) {
        $stmt = $this->pdo->prepare('SELECT * FROM sites WHERE slug = ?');
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function create($userId, $name, $slug, $template = 'blank') {
        $stmt = $this->pdo->prepare('
            INSERT INTO sites (user_id, name, slug, template, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ');
        $stmt->execute([$userId, $name, $slug, $template]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($siteId, $name, $slug) {
        $stmt = $this->pdo->prepare('
            UPDATE sites SET name = ?, slug = ?, updated_at = NOW()
            WHERE id = ?
        ');
        $stmt->execute([$name, $slug, $siteId]);
    }
    
    public function delete($siteId) {
        $stmt = $this->pdo->prepare('DELETE FROM sites WHERE id = ?');
        $stmt->execute([$siteId]);
    }
}