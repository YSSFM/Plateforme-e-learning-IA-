<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AI Courses')</title>

    <!-- Vite pour les assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', "Segoe UI", sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #58cc02, #1cb0f6);
            background-attachment: fixed;
            color: #1f2937;
        }

        h1, h2, h3, .brand {
            font-family: 'Baloo 2', 'Inter', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        /* --- Header --- */
        .header {
            margin: 20px;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-name {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0.01em;
            background: linear-gradient(90deg, #146c43, #0b4f76);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            position: relative;
            line-height: 1;
        }

        .brand-tag {
            font-family: 'Inter', sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #1f2937;
            opacity: 0.6;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .header-nav-links {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        .header-nav-links a {
            margin-left: 0;
            margin-right: 24px;
            text-decoration: none;
            color: #1f2937;
            font-weight: 600;
            opacity: 0.85;
        }

        .header-nav-links a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .header a {
            margin-left: 20px;
            text-decoration: none;
            color: #1f2937;
            font-weight: 600;
        }

        .header a:hover {
            text-decoration: underline;
        }

        .header-right {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            background: #58cc02;
            color: #fff;
            border-radius: 30px;
            text-decoration: none !important;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-outline {
            padding: 10px 22px;
            border: 2px solid #1f2937;
            border-radius: 30px;
            color: #1f2937 !important;
            background: transparent;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
            text-decoration: none !important;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
            padding: 10px 22px;
            border-radius: 30px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .alert-success {
            background: rgba(0, 255, 0, 0.15);
            color: #0b610b;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-error {
            background: rgba(255, 0, 0, 0.15);
            color: #b00020;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* --- Footer --- */
        .site-footer {
            margin: 60px 20px 20px;
            padding: 0;
            overflow: hidden;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr 1fr;
            gap: 30px;
            padding: 40px;
        }

        .footer-grid h4 {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 14px;
            opacity: 0.85;
        }

        .footer-grid p, .footer-grid a {
            display: block;
            font-size: 0.92rem;
            color: #1f2937;
            text-decoration: none;
            margin-bottom: 8px;
            opacity: 0.85;
        }

        .footer-grid a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.35);
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 0.82rem;
            opacity: 0.75;
        }

        @media (max-width: 800px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .header-nav-links { display: none; }
        }
    </style>
</head>
<body>

<header class="glass header">
    <a href="{{ route('home') }}" class="brand-mark">
        <div>
            <span class="brand-name">AI Courses</span>
            <span class="brand-tag">by YSSFM</span>
        </div>
    </a>

    <nav class="header-nav-links">
        <a href="{{ route('home') }}#a-propos">À propos</a>
        <a href="{{ route('home') }}#evenements">Événements</a>
    </nav>

    <nav class="header-right">
        @auth
            <span>Bonjour, {{ Auth::user()->username }}</span>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; cursor: pointer; margin-left: 20px; font-weight: 600; color: #1f2937;">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}">Connexion</a>
            <a href="{{ route('register') }}" class="btn-outline">Inscription</a>
        @endauth
    </nav>
</header>

<main>
    @if(session('success'))
        <div class="alert-success" style="max-width: 800px; margin: 20px auto;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error" style="max-width: 800px; margin: 20px auto;">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</main>

<footer class="site-footer glass">
    <div class="footer-grid">
        <div>
            <h4 class="brand-name" style="font-size: 1.2rem; -webkit-text-fill-color: initial; background: none; color: #1f2937; opacity: 1;">AI Courses</h4>
            <p style="margin-top: 8px;">Plateforme e-learning gratuite dédiée à l'intelligence artificielle et aux technologies émergentes, pensée pour les étudiants de niveau DUT.</p>
        </div>
        <div>
            <h4>Navigation</h4>
            <a href="{{ route('home') }}">Accueil</a>
            <a href="{{ route('home') }}#a-propos">À propos</a>
            <a href="{{ route('home') }}#evenements">Événements</a>
            <a href="{{ route('login') }}">Connexion</a>
        </div>
        <div>
            <h4>Ressources</h4>
            <a href="https://www.geeksforgeeks.org/" target="_blank" rel="noopener">GeeksforGeeks</a>
            <a href="https://www.kaggle.com/" target="_blank" rel="noopener">Kaggle</a>
            <a href="https://arxiv.org/list/cs.AI/recent" target="_blank" rel="noopener">arXiv — cs.AI</a>
            <a href="https://www.freecodecamp.org/" target="_blank" rel="noopener">freeCodeCamp</a>
        </div>
        <div>
            <h4>Contact</h4>
            <a href="mailto:yssfmoussa@gmail.com">yssfmoussa@gmail.com</a>
            <a href="tel:+212627465399">+212 6 27 46 53 99</a>
            <a href="https://www.linkedin.com/in/youssouf-moussa-54220b388/" target="_blank" rel="noopener">LinkedIn</a>
            <a href="https://github.com/YSSFM" target="_blank" rel="noopener">GitHub</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} AI Courses — YSSFM</span>
        <span>Technologies émergentes & Intelligence Artificielle</span>
    </div>
</footer>

</body>
</html>