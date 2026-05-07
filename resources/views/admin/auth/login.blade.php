<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - PARDOX</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Barlow+Condensed:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            background-color: #081a1f; /* Dark Teal */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            height: 500px;
            display: flex;
        }

        .pane-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid rgba(255, 255, 255, 0.4);
            flex-direction: column;
            gap: 1rem;
        }

        .pane-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* Branding */
        .brand-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1a56ff, #f97316); /* PARDOX gradient */
            border-radius: 50% 50% 0 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 2.5rem;
            color: #fff;
            transform: rotate(-15deg);
        }

        .brand-text {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 2.2rem;
            font-weight: 400;
            letter-spacing: 0.05em;
        }

        /* Right Pane Elements */
        h1 {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
        }

        .subtitle {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #b0c4c4;
            margin-bottom: 3rem;
        }

        .login-form {
            width: 100%;
            max-width: 320px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: none;
            border-radius: 4px;
            background-color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            outline: none;
        }

        input::placeholder {
            color: #a0a0a0;
        }

        .btn-login {
            margin-top: 1.5rem;
            background-color: #f36d33;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: #e05c24;
        }

        .forgot-pwd {
            margin-top: 1.5rem;
            font-size: 0.75rem;
            text-align: center;
            text-decoration: none;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .forgot-pwd:hover {
            opacity: 1;
        }
        
        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 0.8rem;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }
            .pane-left {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.4);
                padding: 3rem 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Left Side: Branding -->
        <div class="pane-left">
            <img src="{{ asset('images/logo-white.png') }}" style="width: 200px; height: auto;" alt="PARDOX">
        </div>

        <!-- Right Side: Login -->
        <div class="pane-right">
            <h1>Bienvenue</h1>
            <div class="subtitle">Veuillez vous connecter au tableau de bord.</div>
            
            <form action="{{ route('admin.login.submit') }}" method="POST" class="login-form">
                @csrf
                
                @if($errors->any())
                    <div class="alert-error">Identifiants incorrects.</div>
                @endif
                
                <!-- We use phone as the literal input since PARDOX uses phone/email as primary -->
                <input type="text" name="phone" placeholder="IDENTIFIANT" required autofocus>
                
                <input type="password" name="password" placeholder="MOT DE PASSE" required>
                
                <button type="submit" class="btn-login">Connexion</button>
                

            </form>
        </div>
    </div>

</body>
</html>
