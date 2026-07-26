@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<style>
    .hero-wrap {
        max-width: 960px;
        margin: 30px auto 50px;
        padding: 60px 50px;
        text-align: center;
        position: relative;
    }
    .hero-eyebrow {
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: rgba(255,255,255,0.35);
        padding: 6px 16px;
        border-radius: 20px;
        margin-bottom: 20px;
    }
    .hero-wrap h1 {
        font-size: 2.6rem;
        line-height: 1.15;
        margin-bottom: 14px;
    }
    .hero-underline {
        display: block;
        width: 180px;
        height: 14px;
        margin: 4px auto 18px;
    }
    .hero-wrap .lead {
        font-size: 1.15rem;
        max-width: 620px;
        margin: 0 auto 32px;
        opacity: 0.9;
        line-height: 1.6;
    }
    .hero-ctas {
        display: flex;
        gap: 14px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .section {
        max-width: 1100px;
        margin: 0 auto 50px;
        padding: 0 20px;
        scroll-margin-top: 100px;
    }
    .section-head {
        text-align: center;
        margin-bottom: 32px;
    }
    .section-head .eyebrow {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        opacity: 0.65;
        margin-bottom: 6px;
        display: block;
    }
    .section-head h2 {
        font-size: 1.9rem;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }
    .feature-card {
        padding: 30px;
    }
    .feature-card .emoji {
        font-size: 1.8rem;
        margin-bottom: 10px;
        display: block;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 24px;
    }
    @media (max-width: 800px) {
        .about-grid { grid-template-columns: 1fr; }
    }
    .about-card {
        padding: 36px;
    }
    .dev-card {
        padding: 36px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .dev-avatar {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: linear-gradient(135deg, #58cc02, #1cb0f6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin-bottom: 16px;
        border: 3px solid rgba(255,255,255,0.6);
    }
    .dev-name {
        font-size: 1.2rem;
        font-weight: 700;
    }
    .dev-role {
        font-size: 0.85rem;
        opacity: 0.7;
        margin-bottom: 14px;
    }
    .dev-links {
        display: flex;
        gap: 10px;
        margin-top: 6px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .dev-contact {
        font-size: 0.85rem;
        opacity: 0.8;
        margin-top: 12px;
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }
    .event-card {
        padding: 26px;
        text-decoration: none !important;
        display: block;
        transition: transform 0.2s;
        color: #1f2937 !important;
    }
    .event-card:hover {
        transform: translateY(-4px);
    }
    .event-card .event-tag {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.6;
        margin-bottom: 8px;
        display: block;
    }
    .event-card h3 {
        font-size: 1.15rem;
        margin-bottom: 8px;
    }
    .event-card p {
        font-size: 0.9rem;
        opacity: 0.85;
        margin-bottom: 12px;
        line-height: 1.5;
    }
    .event-card .event-cta {
        font-size: 0.85rem;
        font-weight: 700;
    }
</style>

<!-- HERO -->
<section class="hero-wrap glass">
    <span class="hero-eyebrow">🎓 Plateforme gratuite · Niveau DUT</span>
    <h1>Envie d'apprendre l'IA ?</h1>
    <svg class="hero-underline" viewBox="0 0 180 14" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 10 C 40 2, 80 2, 90 8 S 140 14, 176 6" stroke="#146c43" stroke-width="4" stroke-linecap="round" fill="none" opacity="0.5"/>
    </svg>
    <p class="lead">
        Apprenez l'intelligence artificielle et les technologies émergentes gratuitement,
        à votre rythme et en toute simplicité — cours, exercices corrigés et suivi de progression.
    </p>
    <div class="hero-ctas">
        <a href="{{ route('register') }}" class="btn">Commencer maintenant</a>
        <a href="{{ route('login') }}" class="btn-outline">Se connecter</a>
    </div>
</section>

<!-- POURQUOI -->
<section class="section">
    <div class="feature-grid">
        <div class="glass feature-card">
            <span class="emoji">📚</span>
            <h2 style="font-size: 1.2rem;">Pourquoi AI Courses ?</h2>
            <p>Une plateforme e-learning dédiée exclusivement à l'IA et aux technologies émergentes pour les étudiants de niveau DUT.</p>
        </div>
        <div class="glass feature-card">
            <span class="emoji">🔑</span>
            <h2 style="font-size: 1.2rem;">Accéder aux cours</h2>
            <p>Connectez-vous pour découvrir les modules, suivre votre progression et accéder aux exercices.</p>
            <a href="{{ route('login') }}" class="btn-outline" style="margin-top: 15px;">Se connecter</a>
        </div>
    </div>
</section>

<!-- À PROPOS -->
<section class="section" id="a-propos">
    <div class="section-head">
        <span class="eyebrow">À propos</span>
        <h2>La plateforme et son créateur</h2>
    </div>
    <div class="about-grid">
        <div class="glass about-card">
            <h3 style="margin-bottom: 12px;">🎯 Notre mission</h3>
            <p style="line-height: 1.7; opacity: 0.9;">
                AI Courses est né d'un constat simple : les étudiants en DUT ont rarement accès à des
                ressources structurées et gratuites en intelligence artificielle. Cette plateforme
                regroupe des modules de cours, des exercices pratiques corrigés et un suivi de
                progression, pour apprendre l'IA pas à pas, sans barrière financière.
            </p>
            <p style="line-height: 1.7; opacity: 0.9; margin-top: 14px;">
                Chaque module est pensé pour être suivi de manière autonome, avec des exercices
                concrets et un retour personnalisé de l'équipe pédagogique sur chaque devoir soumis.
            </p>
        </div>
        <div class="glass dev-card">
            <div class="dev-avatar">👨‍💻</div>
            <div class="dev-name">Youssouf Moussa</div>
            <div class="dev-role">Créateur & développeur de la plateforme</div>
            <p style="font-size: 0.9rem; opacity: 0.85; line-height: 1.6;">
                Étudiant passionné d'intelligence artificielle, j'ai conçu AI Courses pour partager
                des ressources claires et accessibles avec mes pairs.
            </p>
            <div class="dev-links">
                <a href="https://www.linkedin.com/in/youssouf-moussa-54220b388/" target="_blank" rel="noopener" class="btn-outline" style="padding: 6px 16px; font-size: 0.85rem;">💼 LinkedIn</a>
                <a href="https://github.com/YSSFM" target="_blank" rel="noopener" class="btn-outline" style="padding: 6px 16px; font-size: 0.85rem;">🐙 GitHub</a>
                <a href="mailto:yssfmoussa@gmail.com" class="btn-outline" style="padding: 6px 16px; font-size: 0.85rem;">✉️ Email</a>
            </div>
            <p class="dev-contact">📞 <a href="tel:+212627465399" style="color: inherit; text-decoration: none;">+212 6 27 46 53 99</a></p>
        </div>
    </div>
</section>

<!-- ÉVÉNEMENTS / RESSOURCES -->
<section class="section" id="evenements">
    <div class="section-head">
        <span class="eyebrow">Pour aller plus loin</span>
        <h2>Événements & ressources utiles</h2>
    </div>
    <div class="event-grid">
        <a href="https://www.geeksforgeeks.org/artificial-intelligence/" target="_blank" rel="noopener" class="glass event-card">
            <span class="event-tag">Tutoriels</span>
            <h3>GeeksforGeeks — IA</h3>
            <p>Tutoriels et exercices pratiques en algorithmique, structures de données et intelligence artificielle.</p>
            <span class="event-cta">Consulter →</span>
        </a>
        <a href="https://www.kaggle.com/competitions" target="_blank" rel="noopener" class="glass event-card">
            <span class="event-tag">Compétitions</span>
            <h3>Kaggle</h3>
            <p>Participez à des compétitions de data science et machine learning, avec des jeux de données réels.</p>
            <span class="event-cta">Consulter →</span>
        </a>
        <a href="https://www.freecodecamp.org/news/tag/machine-learning/" target="_blank" rel="noopener" class="glass event-card">
            <span class="event-tag">Cours gratuits</span>
            <h3>freeCodeCamp</h3>
            <p>Certifications et articles gratuits en programmation, data science et machine learning.</p>
            <span class="event-cta">Consulter →</span>
        </a>
        <a href="https://arxiv.org/list/cs.AI/recent" target="_blank" rel="noopener" class="glass event-card">
            <span class="event-tag">Recherche</span>
            <h3>arXiv — cs.AI</h3>
            <p>Suivez les dernières publications scientifiques en intelligence artificielle, en accès libre.</p>
            <span class="event-cta">Consulter →</span>
        </a>
    </div>
</section>

@endsection