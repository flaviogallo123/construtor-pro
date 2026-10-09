<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /construtor-sites/login');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'Usuário';
$userEmail = $_SESSION['user_email'] ?? '';
$userInitial = strtoupper(substr($userName, 0, 1));

// Buscar sites
$stmt = $pdo->prepare('
    SELECT s.*, 
           COUNT(DISTINCT p.id) as pages_count,
           (SELECT COUNT(*) FROM elements e 
            JOIN pages p2 ON e.page_id = p2.id 
            WHERE p2.site_id = s.id) as total_elements,
           (SELECT id FROM pages WHERE site_id = s.id ORDER BY id LIMIT 1) as first_page_id
    FROM sites s
    LEFT JOIN pages p ON s.id = p.site_id
    WHERE s.user_id = ?
    GROUP BY s.id
    ORDER BY s.created_at DESC
');
$stmt->execute([$userId]);
$sites = $stmt->fetchAll();

$totalSites = count($sites);
$totalPages = array_sum(array_column($sites, 'pages_count'));
$totalElements = array_sum(array_column($sites, 'total_elements'));

// Sites publicados vs rascunho
$publishedCount = count(array_filter($sites, fn($s) => $s['is_published']));
$draftCount = $totalSites - $publishedCount;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Construtor Pro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg: #09090b;
            --bg-secondary: #111113;
            --bg-tertiary: #18181b;
            --bg-hover: #27272a;
            --border: #27272a;
            --border-light: #3f3f46;
            --text: #fafafa;
            --text-secondary: #a1a1aa;
            --text-tertiary: #71717a;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --accent-glow: rgba(59, 130, 246, 0.15);
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --purple: #8b5cf6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background effects */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -30%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--purple));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .logo-text span {
            color: var(--text-secondary);
            font-weight: 400;
            font-size: 12px;
            display: block;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
            cursor: pointer;
        }

        .nav-item:hover {
            background: var(--bg-hover);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--accent-glow);
            color: var(--accent);
        }

        .nav-item i {
            width: 18px;
            font-size: 14px;
        }

        .nav-item .badge {
            margin-left: auto;
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .nav-item .shortcut {
            margin-left: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text-tertiary);
            background: var(--bg-tertiary);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid var(--border);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .user-card:hover {
            background: var(--bg-hover);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 11px;
            color: var(--text-tertiary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* User Dropdown */
        .user-dropdown {
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px;
            margin-bottom: 8px;
            display: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .user-dropdown.show {
            display: block;
            animation: slideUp 0.2s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: var(--bg-hover);
            color: var(--text);
        }

        .dropdown-item.danger {
            color: var(--error);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }

        /* Main Content */
        .main {
            flex: 1;
            margin-left: 260px;
            padding: 32px 40px;
            position: relative;
            z-index: 1;
        }

        /* Top Bar */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            animation: fadeIn 0.5s ease-out;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 500px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--accent);
            background: var(--bg-tertiary);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            font-size: 14px;
        }

        .search-box .search-shortcut {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text-tertiary);
            background: var(--bg-tertiary);
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid var(--border);
        }

        .topbar-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            background: var(--bg-hover);
            color: var(--text);
            border-color: var(--border-light);
        }

        .icon-btn .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--error);
            border-radius: 50%;
            border: 2px solid var(--bg);
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        /* Welcome */
        .welcome {
            margin-bottom: 32px;
            animation: fadeIn 0.5s ease-out 0.1s both;
        }

        .welcome h1 {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 6px;
        }

        .welcome p {
            color: var(--text-secondary);
            font-size: 15px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
            animation: fadeIn 0.5s ease-out 0.2s both;
        }

        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: var(--border-light);
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
            color: var(--success);
            background: rgba(16, 185, 129, 0.1);
            padding: 3px 8px;
            border-radius: 6px;
        }

        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
        }

        /* Charts & Activity Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
            animation: fadeIn 0.5s ease-out 0.3s both;
        }

        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
        }

        .card-subtitle {
            font-size: 12px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        .chart-container {
            position: relative;
            height: 240px;
        }

        /* Activity Timeline */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            position: relative;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 36px;
            bottom: -16px;
            width: 2px;
            background: var(--border);
        }

        .activity-item:last-child::before {
            display: none;
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--bg-tertiary);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
            z-index: 1;
        }

        .activity-icon.blue { background: rgba(59, 130, 246, 0.2); color: var(--accent); border-color: var(--accent); }
        .activity-icon.green { background: rgba(16, 185, 129, 0.2); color: var(--success); border-color: var(--success); }
        .activity-icon.purple { background: rgba(139, 92, 246, 0.2); color: var(--purple); border-color: var(--purple); }

        .activity-content {
            flex: 1;
            min-width: 0;
        }

        .activity-title {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .activity-time {
            font-size: 11px;
            color: var(--text-tertiary);
        }

        /* Sites Section */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-out 0.4s both;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
        }

        .filter-tabs {
            display: flex;
            gap: 4px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 4px;
        }

        .filter-tab {
            padding: 6px 14px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: transparent;
            font-family: 'Inter', sans-serif;
        }

        .filter-tab.active {
            background: var(--bg-hover);
            color: var(--text);
        }

        .sites-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
            animation: fadeIn 0.5s ease-out 0.5s both;
        }

        .site-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            transition: all 0.3s;
            position: relative;
        }

        .site-card:hover {
            border-color: var(--border-light);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .site-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .site-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--purple));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-badge.published {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-badge.draft {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .site-card-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .site-card-meta {
            display: flex;
            gap: 16px;
            margin-bottom: 16px;
            font-size: 12px;
            color: var(--text-tertiary);
        }

        .site-card-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .site-card-actions {
            display: flex;
            gap: 8px;
        }

        .site-card-actions .btn {
            flex: 1;
            padding: 8px;
            font-size: 13px;
            justify-content: center;
        }

        .btn-ghost {
            background: var(--bg-tertiary);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover {
            background: var(--bg-hover);
            color: var(--text);
        }

        .btn-export {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
            border: none;
            padding: 8px;
            font-size: 13px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        /* Empty State */
        .empty-state {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 80px 40px;
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 24px;
            border: 1px solid var(--border);
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 24px;
        }

        /* Command Palette */
        .command-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding-top: 15vh;
        }

        .command-overlay.show {
            display: flex;
            animation: fadeIn 0.15s ease-out;
        }

        .command-palette {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 14px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            animation: slideDown 0.2s ease-out;
            overflow: hidden;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .command-input-wrapper {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }

        .command-input-wrapper i {
            color: var(--text-tertiary);
        }

        .command-input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text);
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            outline: none;
        }

        .command-input::placeholder {
            color: var(--text-tertiary);
        }

        .command-list {
            padding: 8px;
            max-height: 400px;
            overflow-y: auto;
        }

        .command-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            color: var(--text);
        }

        .command-item:hover, .command-item.selected {
            background: var(--bg-hover);
        }

        .command-item i {
            width: 20px;
            color: var(--text-secondary);
        }

        .command-item-text {
            flex: 1;
            font-size: 14px;
        }

        .command-item-shortcut {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text-tertiary);
        }

        .command-section-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 12px 6px;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--border-light); }

        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; padding: 20px; }
            .sites-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">🚀</div>
            <div class="logo-text">
                Construtor Pro
                <span>v2.0 Enterprise</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Principal</div>
                <a href="/construtor-sites/dashboard" class="nav-item active">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
                <a href="/construtor-sites/editor" class="nav-item">
                    <i class="fas fa-plus"></i>
                    Novo Site
                    <span class="shortcut">N</span>
                </a>
                <div class="nav-item" onclick="openCommandPalette()">
                    <i class="fas fa-search"></i>
                    Buscar
                    <span class="shortcut">⌘K</span>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Seus Sites</div>
                <?php foreach (array_slice($sites, 0, 5) as $site): ?>
                <a href="/construtor-sites/editor?page=<?= $site['first_page_id'] ?? 1 ?>&site=<?= $site['id'] ?>" class="nav-item">
                    <i class="fas fa-globe"></i>
                    <?= htmlspecialchars($site['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Configurações</div>
                <a href="#" class="nav-item">
                    <i class="fas fa-cog"></i>
                    Preferências
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-question-circle"></i>
                    Ajuda
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card" onclick="toggleUserDropdown()">
                <div class="user-avatar"><?= $userInitial ?></div>
                <div class="user-info">
                    <div class="user-name"><?= htmlspecialchars($userName) ?></div>
                    <div class="user-email"><?= htmlspecialchars($userEmail) ?></div>
                </div>
                <i class="fas fa-ellipsis-v" style="color: var(--text-tertiary);"></i>

                <div class="user-dropdown" id="userDropdown">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i> Meu Perfil
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i> Configurações
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="/construtor-sites/api/auth/logout" class="dropdown-item danger">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar sites, páginas..." id="searchInput" oninput="filterSites(this.value)">
                <span class="search-shortcut">K</span>
            </div>
            <div class="topbar-actions">
                <button class="icon-btn" title="Notificações">
                    <i class="fas fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
                <a href="/construtor-sites/editor" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Novo Site
                </a>
            </div>
        </div>

        <!-- Welcome -->
        <div class="welcome">
            <h1>Bom dia, <?= htmlspecialchars($userName) ?> 👋</h1>
            <p>Vamos criar algo incrível hoje?</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">🌐</div>
                    <?php if ($totalSites > 0): ?>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i> Ativo
                    </div>
                    <?php endif; ?>
                </div>
                <div class="stat-value"><?= $totalSites ?></div>
                <div class="stat-label">Sites Criados</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">📄</div>
                </div>
                <div class="stat-value"><?= $totalPages ?></div>
                <div class="stat-label">Páginas Totais</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">🎨</div>
                </div>
                <div class="stat-value"><?= $totalElements ?></div>
                <div class="stat-label">Elementos</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon">✅</div>
                    <div class="stat-trend">
                        <i class="fas fa-check"></i>
                        <?= $publishedCount ?> pub.
                    </div>
                </div>
                <div class="stat-value"><?= $draftCount ?></div>
                <div class="stat-label">Rascunhos</div>
            </div>
        </div>

        <!-- Charts & Activity -->
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Visão Geral</div>
                        <div class="card-subtitle">Seus últimos 7 dias</div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <div class="card-title">Atividade Recente</div>
                        <div class="card-subtitle">Últimas ações</div>
                    </div>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon blue"><i class="fas fa-rocket"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Sistema inicializado</div>
                            <div class="activity-time">Agora mesmo</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon green"><i class="fas fa-check"></i></div>
                        <div class="activity-content">
                            <div class="activity-title"><?= $totalSites ?> sites criados</div>
                            <div class="activity-time">Total acumulado</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon purple"><i class="fas fa-paint-brush"></i></div>
                        <div class="activity-content">
                            <div class="activity-title"><?= $totalElements ?> elementos usados</div>
                            <div class="activity-time">Em <?= $totalPages ?> páginas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sites Section -->
        <div class="section-header">
            <h2 class="section-title">Seus Sites</h2>
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterByStatus('all', this)">Todos</button>
                <button class="filter-tab" onclick="filterByStatus('published', this)">Publicados</button>
                <button class="filter-tab" onclick="filterByStatus('draft', this)">Rascunhos</button>
            </div>
        </div>

        <?php if (empty($sites)): ?>
        <div class="empty-state">
            <div class="empty-icon">🚀</div>
            <h3>Comece seu primeiro projeto</h3>
            <p>Crie um site profissional em minutos com nosso construtor visual.</p>
            <a href="/construtor-sites/editor" class="btn btn-primary">
                <i class="fas fa-magic"></i> Criar Meu Primeiro Site
            </a>
        </div>
        <?php else: ?>
        <div class="sites-grid" id="sitesGrid">
            <?php foreach ($sites as $site): ?>
            <div class="site-card" data-status="<?= $site['is_published'] ? 'published' : 'draft' ?>" data-name="<?= strtolower($site['name']) ?>">
                <div class="site-card-header">
                    <div class="site-card-icon">🌐</div>
                    <span class="status-badge <?= $site['is_published'] ? 'published' : 'draft' ?>">
                        <?= $site['is_published'] ? 'Publicado' : 'Rascunho' ?>
                    </span>
                </div>
                <h3 class="site-card-name"><?= htmlspecialchars($site['name']) ?></h3>
                <div class="site-card-meta">
                    <span><i class="fas fa-layer-group"></i> <?= $site['pages_count'] ?> páginas</span>
                    <span><i class="fas fa-cube"></i> <?= $site['total_elements'] ?> elementos</span>
                </div>
                <div class="site-card-actions">
                    <a href="/construtor-sites/editor?page=<?= $site['first_page_id'] ?? 1 ?>&site=<?= $site['id'] ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="/construtor-sites/<?= htmlspecialchars($site['slug']) ?>" target="_blank" class="btn btn-ghost">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button onclick="exportSite(<?= $site['id'] ?>)" class="btn-export">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <!-- Command Palette -->
    <div class="command-overlay" id="commandOverlay" onclick="closeCommandPalette(event)">
        <div class="command-palette" onclick="event.stopPropagation()">
            <div class="command-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" class="command-input" id="commandInput" placeholder="Digite um comando ou busque..." oninput="filterCommands(this.value)">
                <span style="font-family: 'JetBrains Mono'; font-size: 11px; color: var(--text-tertiary); background: var(--bg-tertiary); padding: 3px 8px; border-radius: 4px; border: 1px solid var(--border);">ESC</span>
            </div>
            <div class="command-list" id="commandList">
                <div class="command-section-title">Ações Rápidas</div>
                <a href="/construtor-sites/editor" class="command-item">
                    <i class="fas fa-plus"></i>
                    <span class="command-item-text">Criar novo site</span>
                    <span class="command-item-shortcut">N</span>
                </a>
                <div class="command-item" onclick="closeCommandPalette()">
                    <i class="fas fa-search"></i>
                    <span class="command-item-text">Buscar sites</span>
                    <span class="command-item-shortcut">⌘K</span>
                </div>

                <div class="command-section-title">Navegação</div>
                <a href="/construtor-sites/dashboard" class="command-item">
                    <i class="fas fa-home"></i>
                    <span class="command-item-text">Dashboard</span>
                </a>
                <a href="/construtor-sites/api/auth/logout" class="command-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="command-item-text">Sair</span>
                </a>

                <?php if (!empty($sites)): ?>
                <div class="command-section-title">Seus Sites</div>
                <?php foreach ($sites as $site): ?>
                <a href="/construtor-sites/editor?page=<?= $site['first_page_id'] ?? 1 ?>&site=<?= $site['id'] ?>" class="command-item">
                    <i class="fas fa-globe"></i>
                    <span class="command-item-text"><?= htmlspecialchars($site['name']) ?></span>
                </a>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Chart.js
        const ctx = document.getElementById('activityChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Atividade',
                    data: [2, 4, 3, 5, <?= $totalElements ?>, <?= $totalElements ?>, <?= $totalElements ?>],
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#71717a', font: { family: 'Inter', size: 11 } }
                    },
                    y: {
                        grid: { color: '#27272a', drawBorder: false },
                        ticks: { color: '#71717a', font: { family: 'JetBrains Mono', size: 11 } }
                    }
                }
            }
        });

        // User Dropdown
        function toggleUserDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.user-card')) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        });

        // Command Palette
        function openCommandPalette() {
            document.getElementById('commandOverlay').classList.add('show');
            setTimeout(() => document.getElementById('commandInput').focus(), 100);
        }

        function closeCommandPalette(e) {
            if (e && e.target !== e.currentTarget) return;
            document.getElementById('commandOverlay').classList.remove('show');
        }

        function filterCommands(query) {
            const items = document.querySelectorAll('.command-item');
            const q = query.toLowerCase();
            items.forEach(item => {
                const text = item.querySelector('.command-item-text').textContent.toLowerCase();
                item.style.display = text.includes(q) ? 'flex' : 'none';
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                openCommandPalette();
            }
            if (e.key === 'Escape') {
                closeCommandPalette();
            }
            if (e.key === 'n' && !e.target.matches('input, textarea')) {
                e.preventDefault();
                window.location.href = '/construtor-sites/editor';
            }
        });

        // Search filter
        function filterSites(query) {
            const q = query.toLowerCase();
            document.querySelectorAll('.site-card').forEach(card => {
                const name = card.dataset.name;
                card.style.display = name.includes(q) ? 'block' : 'none';
            });
        }

        // Filter by status
        function filterByStatus(status, btn) {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.site-card').forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Export Site
       // Export Site
async function exportSite(siteId) {
    if (!confirm('Exportar site como HTML estático (ZIP)?')) return;
    
    try {
        const response = await fetch('/construtor-sites/api/export', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ site_id: siteId })
        });
        
        if (response.ok) {
            // Download direto via blob
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'site-' + siteId + '.zip';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            alert('✅ Site exportado com sucesso!');
        } else {
            const error = await response.json();
            alert('❌ Erro: ' + (error.error || 'Erro ao exportar'));
        }
    } catch (error) {
        alert('❌ Erro: ' + error.message);
    }
}
    </script>
</body>
</html>