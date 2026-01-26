@extends('layouts.guest')

@section('title', 'Connexion - Gestion AVS')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 100px; max-width: 300px;">

            <p class="subtitle">Système de Gestion AVS</p>
        </div>

        <div id="message"></div>

        <form id="loginForm" onsubmit="handleLogin(event)">
            <div class="form-group">
                <label for="email">
                    <i class="bi bi-envelope"></i>
                    Email
                </label>
                <input type="email" id="email" name="email" class="form-control" required 
                       placeholder="votre@email.com" autocomplete="email">
            </div>

            <div class="form-group">
                <label for="mdp">
                    <i class="bi bi-lock"></i>
                    Mot de passe
                </label>
                <div class="password-wrapper">
                    <input type="password" id="mdp" name="mdp" class="form-control" required 
                           placeholder="••••••••" autocomplete="current-password">
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Connexion</span>
            </button>
        </form>

        <div class="login-footer">
            <p>&copy; {{ date('Y') }} Gestion AVS - Tous droits réservés</p>
        </div>

        <div class="demo-credentials">
            <h4><i class="bi bi-info-circle"></i> Identifiants de test</h4>
            <div class="credentials-list">
                <div class="credential-item">
                    <span class="role">Acheteur</span>
                    <span class="email">acheteur@example.com</span>
                </div>
                <div class="credential-item">
                    <span class="role">Magasinier</span>
                    <span class="email">magasinier@example.com</span>
                </div>
                <div class="credential-item">
                    <span class="role">Directeur</span>
                    <span class="email">directeur@example.com</span>
                </div>
            </div>
            <p class="password-hint"><i class="bi bi-key"></i> Mot de passe: <code>password123</code></p>
        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --primary-blue: #0056b3;
        --dark-blue: #003d82;
        --white: #ffffff;
        --text-dark: #333333;
        --text-light: #666666;
        --border-gray: #e0e0e0;
        --light-gray: #f5f5f5;
        --light-blue: #e8f0fd;
        --transition: all 0.3s ease;
    }

    body {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .login-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        padding: 40px;
        animation: slideUp 0.5s ease-out;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-container {
        width: 100%;
        max-width: 420px;
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-header img {
        margin-bottom: 15px;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
    }

    .login-header h1 {
        color: var(--primary-blue);
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 5px;
        letter-spacing: 1px;
    }

    .login-header .subtitle {
        color: var(--text-light);
        font-size: 14px;
        margin: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: var(--text-dark);
        font-weight: 500;
        font-size: 14px;
    }

    .form-group label i {
        color: var(--primary-blue);
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border-gray);
        border-radius: 10px;
        font-size: 14px;
        transition: var(--transition);
        background: var(--light-gray);
        color: var(--text-dark);
        font-family: inherit;
    }

    .form-control::placeholder {
        color: var(--text-light);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-blue);
        background: var(--white);
        box-shadow: 0 0 0 4px rgba(0, 86, 179, 0.1);
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 45px;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        padding: 5px;
        transition: var(--transition);
    }

    .toggle-password:hover {
        color: var(--primary-blue);
    }

    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        color: var(--white);
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
        position: relative;
        z-index: 10;
        min-height: 50px;
        box-shadow: 0 4px 12px rgba(0, 86, 179, 0.25);
    }

    .btn-login:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 86, 179, 0.4);
    }

    .btn-login:active:not(:disabled) {
        transform: translateY(-1px);
    }

    .btn-login:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    .btn-login .spinner {
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .alert {
        padding: 12px 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-danger {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .alert-success {
        background-color: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .login-footer {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid var(--border-gray);
    }

    .login-footer p {
        color: var(--text-light);
        font-size: 12px;
    }

    .demo-credentials {
        background: linear-gradient(135deg, var(--light-blue) 0%, rgba(0, 86, 179, 0.05) 100%);
        padding: 15px;
        border-radius: 10px;
        margin-top: 20px;
        border: 1px solid rgba(0, 86, 179, 0.3);
    }

    .demo-credentials h4 {
        color: var(--primary-blue);
        font-size: 14px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .credentials-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 10px;
    }

    .credential-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        padding: 8px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 6px;
    }

    .credential-item .role {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 11px;
    }

    .credential-item .email {
        color: var(--text-dark);
        font-family: 'Courier New', monospace;
        font-size: 11px;
    }

    .password-hint {
        font-size: 12px;
        color: var(--text-dark);
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }

    .password-hint code {
        background: var(--white);
        padding: 3px 10px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        color: var(--primary-blue);
        font-weight: 600;
        border: 1px solid var(--border-gray);
    }
</style>
@endpush

@push('scripts')
<script>
    const API_URL = '/auth';

    function togglePassword() {
        const input = document.getElementById('mdp');
        const icon = document.getElementById('toggleIcon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    async function handleLogin(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const mdp = document.getElementById('mdp').value;
        const btnLogin = document.getElementById('btnLogin');
        const messageDiv = document.getElementById('message');

        // Désactiver le bouton et afficher le spinner
        btnLogin.disabled = true;
        btnLogin.innerHTML = '<div class="spinner"></div><span>Connexion en cours...</span>';

        try {
            const response = await fetch(`${API_URL}/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ email, mdp })
            });

            const data = await response.json();

            if (data.success) {
                // Stocker le token et les infos utilisateur
                localStorage.setItem('jwt_token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));

                showMessage('<i class="bi bi-check-circle"></i> Connexion réussie! Redirection...', 'success');

                // Rediriger vers le dashboard
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1000);
            } else {
                showMessage('<i class="bi bi-exclamation-circle"></i> ' + (data.message || 'Erreur de connexion'), 'danger');
                resetButton();
            }
        } catch (error) {
            showMessage('<i class="bi bi-exclamation-triangle"></i> Erreur de connexion au serveur', 'danger');
            resetButton();
            console.error('Erreur:', error);
        }
    }

    function showMessage(message, type) {
        const messageDiv = document.getElementById('message');
        messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
    }

    function resetButton() {
        const btnLogin = document.getElementById('btnLogin');
        btnLogin.disabled = false;
        btnLogin.innerHTML = '<i class="bi bi-box-arrow-in-right"></i><span>Connexion</span>';
    }

    // Vérifier si l'utilisateur est déjà connecté
    window.addEventListener('load', () => {
        const token = localStorage.getItem('jwt_token');
        if (token) {
            window.location.href = '/dashboard';
        }
    });
</script>
@endpush
@endsection
