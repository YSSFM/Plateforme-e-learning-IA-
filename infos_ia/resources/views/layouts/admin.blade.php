<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', 'AI Courses')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Import d'une police professionnelle */
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
            color: #1a1a2e;
        }

        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .admin-layout {
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
            background: rgba(255, 255, 255, 0.9);
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-weight: 800;
            color: #1a1a2e;
        }

        .sidebar h2 small {
            font-weight: 400;
            color: #4a4a6a;
        }

        .sidebar nav a {
            display: block;
            margin-bottom: 12px;
            font-weight: 600;
            text-decoration: none;
            color: #1a1a2e;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .sidebar nav a:hover {
            background: rgba(88, 204, 2, 0.15);
            color: #2d7d00;
        }

        .sidebar .danger {
            color: #dc3545;
        }

        .sidebar .danger:hover {
            background: rgba(220, 53, 69, 0.15);
        }

        .admin-content {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .block {
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
        }

        .block h1, .block h2, .block h3, .block h4 {
            color: #1a1a2e;
            font-weight: 700;
        }

        .block p, .block label, .block span, .block div {
            color: #2d2d44;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            text-align: left;
            color: #1a1a2e;
        }

        th {
            background: rgba(0, 0, 0, 0.05);
            font-weight: 700;
            color: #1a1a2e;
        }

        td {
            color: #2d2d44;
        }

        .btn {
            display: inline-block;
            padding: 10px 24px;
            background: #58cc02;
            color: #ffffff !important;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .btn:hover {
            background: #4ab802;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(88, 204, 2, 0.3);
            color: #ffffff !important;
        }

        .btn-outline {
            padding: 8px 20px;
            border: 2px solid #58cc02;
            border-radius: 30px;
            color: #2d7d00 !important;
            background: transparent;
            display: inline-block;
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: rgba(88, 204, 2, 0.1);
            border-color: #4ab802;
            color: #1a5a00 !important;
        }

        .btn-danger {
            background: #dc3545;
            color: #ffffff !important;
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            color: #ffffff !important;
        }

        .badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        .badge.actif { background: #c8f7dc; color: #146c43; }
        .badge.bloque { background: #f8d7da; color: #842029; }
        .badge.publie { background: #c8f7dc; color: #146c43; }
        .badge.brouillon { background: #fff3cd; color: #856404; }
        .badge.archive { background: #e9ecef; color: #495057; }

        .alert-success {
            background: rgba(88, 204, 2, 0.15);
            color: #146c43;
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #58cc02;
            font-weight: 500;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.12);
            color: #842029;
            padding: 14px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
            font-weight: 500;
        }

        input, select, textarea {
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 16px;
            width: 100%;
            font-size: 1rem;
            color: #1a1a2e;
            background: rgba(255, 255, 255, 0.8);
            transition: border-color 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #58cc02;
            box-shadow: 0 0 0 3px rgba(88, 204, 2, 0.15);
        }

        input::placeholder, textarea::placeholder {
            color: #8a8aa8;
        }

        label {
            color: #1a1a2e;
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        .admin-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Amélioration du contraste pour les fichiers */
        .file-item {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #1a1a2e;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .file-item span {
            color: #1a1a2e;
            font-weight: 500;
        }

        .file-item a {
            color: #1a1a2e;
            font-weight: 600;
        }

        .file-item a:hover {
            color: #2d7d00;
        }

        /* Lien dans les tableaux */
        table a {
            color: #1a1a2e;
            text-decoration: none;
            font-weight: 500;
        }

        table a:hover {
            color: #2d7d00;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <aside class="sidebar glass">
        <h2>⚡ Admin<br><small>AI Courses</small></h2>
        <nav>
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.modules.index') }}">📦 Modules</a>
            <a href="{{ route('admin.courses.index') }}">📚 Cours</a>
            <a href="{{ route('admin.exercices.index') }}">✍️ Exercices</a>
            <a href="{{ route('admin.users.index') }}">👥 Utilisateurs</a>
            <a href="{{ route('admin.submissions.index') }}">📝 Soumissions</a>
            <a href="{{ route('admin.remarks.index') }}">💬 Remarques</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 30px;">
                @csrf
                <button type="submit" class="danger" style="background: none; border: none; color: #dc3545; cursor: pointer; font-weight: 700; font-size: 1rem; padding: 12px 16px; border-radius: 10px; width: 100%; text-align: left; transition: all 0.2s;">
                    🚪 Déconnexion
                </button>
            </form>
        </nav>
    </aside>

    <main class="admin-content">
        @if(session('success'))
            <div class="alert-success block">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error block">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

</body>
</html>