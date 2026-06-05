<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe - IFAPME</title>
    <style>
        @import url('https://fonts.cdnfonts.com/css/trade-gothic-lt-std');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Trade Gothic LT Std', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #7d003e 0%, #a0004f 50%, #e50043 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .reset-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(125, 0, 62, 0.3);
            overflow: hidden;
            max-width: 550px;
            width: 100%;
        }

        .reset-header {
            background: linear-gradient(135deg, #7d003e 0%, #a0004f 100%);
            padding: 40px 40px 50px;
            color: white;
            text-align: center;
            position: relative;
        }

        .icon-container {
            width: 80px;
            height: 80px;
            background: #e50043;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
        }

        .reset-header h1 {
            font-size: 28px;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .reset-header p {
            font-size: 15px;
            opacity: 0.95;
            line-height: 1.5;
        }

        .reset-body {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #e50043;
            box-shadow: 0 0 0 3px rgba(229, 0, 67, 0.1);
        }

        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin-top: 24px;
            font-size: 13px;
        }

        .password-requirements h4 {
            color: #333;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 600;
        }

        .password-requirements ul {
            list-style: none;
            color: #666;
        }

        .password-requirements li {
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-requirements li:before {
            content: "•";
            color: #e50043;
            font-weight: bold;
            font-size: 18px;
        }

        .btn-reset {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #e50043 0%, #ff1a5c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 24px;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 0, 67, 0.4);
            background: linear-gradient(135deg, #ff1a5c 0%, #e50043 100%);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        @media (max-width: 768px) {
            .reset-header {
                padding: 30px 30px 40px;
            }

            .reset-body {
                padding: 30px;
            }

            .reset-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="reset-container">
    <div class="reset-header">
        <div class="icon-container">
            🔑
        </div>
        <h1>Nouveau mot de passe</h1>
        <p>Choisissez un nouveau mot de passe sécurisé pour votre compte</p>
    </div>

    <div class="reset-body">
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    placeholder="votre.email@ifapme.be"
                >
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="••••••••"
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    placeholder="••••••••"
                >
            </div>

            <div class="password-requirements">
                <h4>Votre mot de passe doit contenir :</h4>
                <ul>
                    <li>Au moins 8 caractères</li>
                    <li>Au moins une lettre majuscule</li>
                    <li>Au moins une lettre minuscule</li>
                    <li>Au moins un chiffre</li>
                    <li>Au moins un caractère spécial (@$!%*?&)</li>
                </ul>
            </div>

            <button type="submit" class="btn-reset">
                Réinitialiser le mot de passe
            </button>
        </form>
    </div>
</div>
</body>
</html>
