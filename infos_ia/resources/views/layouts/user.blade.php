<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Étudiant')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #58cc02, #1cb0f6);
            color: #1f2937;
        }

        .glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        .user-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
            gap: 20px;
            padding: 20px;
        }

        .sidebar {
            padding: 30px;
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-weight: 800;
        }

        .sidebar nav a {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            text-decoration: none;
            color: #1f2937;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .sidebar nav a:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateX(2px);
        }

        .main-content {
            padding: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 24px;
            background: #58cc02;
            color: #fff !important;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .btn:hover {
            background: #4ab802;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(88, 204, 2, 0.35);
        }

        .btn-outline {
            padding: 9px 22px;
            border: 2px solid #ffffff;
            border-radius: 30px;
            color: #ffffff !important;
            background: rgba(255,255,255,0.12);
            display: inline-block;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.28);
        }

        .btn-danger {
            padding: 9px 22px;
            border-radius: 30px;
            background: #dc3545;
            color: #fff !important;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .card {
            padding: 26px 28px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.35);
            margin-bottom: 20px;
        }

        .card h1, .card h2, .card h3, .card h4 {
            color: #1f2937;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .alert-success {
            background: rgba(255, 255, 255, 0.5);
            color: #146c43;
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #146c43;
            font-weight: 600;
        }

        .alert-error {
            background: rgba(255, 255, 255, 0.5);
            color: #842029;
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            font-weight: 600;
        }

        /* --- Tableaux (manquait entièrement, causait un rendu collé) --- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.35);
            color: #1f2937;
        }

        th {
            background: rgba(255, 255, 255, 0.25);
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.15);
        }

        /* --- Champs de formulaire --- */
        input, select, textarea {
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            margin-bottom: 16px;
            width: 100%;
            font-size: 1rem;
            color: #1f2937;
            background: rgba(255, 255, 255, 0.6);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #ffffff;
            background: rgba(255, 255, 255, 0.85);
        }

        label {
            color: #1f2937;
            font-weight: 700;
            display: block;
            margin-bottom: 6px;
        }

        /* --- Badges --- */
        .badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
        }
        .badge.corrige { background: #c8f7dc; color: #146c43; }
        .badge.attente { background: #fff3cd; color: #856404; }

        /* --- Liste de fichiers --- */
        .file-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .file-list a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: rgba(255,255,255,0.4);
            border-radius: 10px;
            color: #1f2937 !important;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        .file-list a:hover {
            background: rgba(255,255,255,0.6);
            transform: translateX(3px);
        }
    </style>
</head>
<body>

<div class="user-layout">
    <aside class="sidebar glass">
        <h2>🎓 AI Courses</h2>
        <nav>
            <a href="{{ route('user.dashboard') }}">🏠 Dashboard</a>
            <a href="{{ route('user.modules.index') }}">📦 Modules</a>
            <a href="{{ route('user.exercises.index') }}">✍️ Exercices</a>
            <a href="{{ route('user.submissions.index') }}">📂 Mes soumissions</a>
            <a href="{{ route('user.progress.index') }}">📊 Progression</a>
            <a href="{{ route('user.profile.edit') }}">👤 Mon profil</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 30px;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #ffe3e3; cursor: pointer; font-weight: 700; padding: 12px 16px;">🚪 Déconnexion</button>
            </form>
        </nav>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>