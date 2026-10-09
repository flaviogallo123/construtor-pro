<?php
session_start();
$_SESSION['user_id'] = 2; // Seu ID de usuário

require_once __DIR__ . '/api/pages.php';