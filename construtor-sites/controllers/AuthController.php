<?php
class AuthController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function register() {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
                return;
            }
            
            $name = trim($input['name']);
            $email = trim($input['email']);
            $password = $input['password'];
            
            // Verificar se email já existe
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Email já cadastrado']);
                return;
            }
            
            // Criptografar senha
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Criar usuário
            $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$name, $email, $hashedPassword]);
            
            $userId = $this->pdo->lastInsertId();
            
            // Criar sessão
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            
            echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function login() {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($input['email']) || !isset($input['password'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Email e senha são obrigatórios']);
                return;
            }
            
            $email = trim($input['email']);
            $password = $input['password'];
            
            // Buscar usuário
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Email ou senha inválidos']);
                return;
            }
            
            // Verificar senha
            if (!password_verify($password, $user['password'])) {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Email ou senha inválidos']);
                return;
            }
            
            // Criar sessão
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso!']);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function logout() {
        session_destroy();
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode(['success' => true]);
        } else {
            header('Location: /construtor-sites/login');
            exit;
        }
    }
    
    public function check() {
        header('Content-Type: application/json');
        
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'loggedIn' => true,
                'user' => [
                    'id' => $_SESSION['user_id'],
                    'name' => $_SESSION['user_name'],
                    'email' => $_SESSION['user_email']
                ]
            ]);
        } else {
            echo json_encode(['loggedIn' => false]);
        }
    }
    
    public function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                http_response_code(401);
                echo json_encode(['success' => false, 'error' => 'Não autorizado']);
                exit;
            } else {
                header('Location: /construtor-sites/login');
                exit;
            }
        }
    }
}