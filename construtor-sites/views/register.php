<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - Construtor de Sites</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .links {
            margin-top: 20px;
            text-align: center;
        }
        .links a {
            color: #667eea;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error {
            background: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 Criar conta</h1>
        <p class="subtitle">Comece a construir seu site</p>
        
        <div id="error" style="display: none;"></div>
        
        <form id="registerForm">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" id="name" required placeholder="Seu nome completo">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" required placeholder="seu@email.com">
            </div>
            
            <div class="form-group">
                <label>Senha</label>
                <input type="password" id="password" required placeholder="Mínimo 6 caracteres" minlength="6">
            </div>
            
            <button type="submit">Criar conta</button>
        </form>
        
        <div class="links">
            <p>Já tem conta? <a href="/construtor-sites/login">Entrar</a></p>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('/construtor-sites/api/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = '/construtor-sites/dashboard';
                } else {
                    const errorDiv = document.getElementById('error');
                    errorDiv.textContent = result.error;
                    errorDiv.style.display = 'block';
                }
            } catch (error) {
                const errorDiv = document.getElementById('error');
                errorDiv.textContent = 'Erro de conexão. Tente novamente.';
                errorDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html>