@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="card">
    <h1>Bienvenue, {{ Auth::user()->username }} ! 👋</h1>
    <p>Voici un aperçu de votre activité sur AI Courses.</p>
</div>

@if(!Auth::user()->niveau_id)
<div class="card" style="background: rgba(255, 255, 0, 0.2);">
    <h3>⚠️ Sélectionnez votre niveau</h3>
    <p>Pour accéder aux cours adaptés à votre parcours, veuillez sélectionner votre niveau (S1, S2, S3, S4).</p>
    <a href="{{ route('user.profile.edit') }}" class="btn">Choisir mon niveau →</a>
</div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
    <div class="card">
        <h3>Modules disponibles</h3>
        @forelse($modules as $module)
            <div style="margin-bottom: 15px;">
                <strong>{{ $module->titre }}</strong>
                <p style="font-size: 0.9rem; opacity: 0.8;">{{ Str::limit($module->description, 80) }}</p>
                <a href="{{ route('user.modules.show', $module->id) }}" class="btn-outline" style="font-size: 0.8rem;">Voir le module →</a>
            </div>
        @empty
            <p>Aucun module disponible pour votre niveau.</p>
        @endforelse
    </div>

    <div class="card">
        <h3>✍️ Exercices à faire</h3>
        @forelse($pendingExercices as $exercice)
            <div style="margin-bottom: 15px;">
                <strong>{{ $exercice->titre }}</strong>
                <p style="font-size: 0.9rem; opacity: 0.8;">{{ Str::limit($exercice->enonce, 80) }}</p>
                <a href="{{ route('user.exercises.show', $exercice->id) }}" class="btn-outline" style="font-size: 0.8rem;">Faire l'exercice →</a>
            </div>
        @empty
            <p>Tous vos exercices sont soumis ! ✅</p>
        @endforelse
    </div>

    <div class="card">
        <h3>Dernières soumissions</h3>
        @forelse($recentSubmissions as $submission)
            <div style="margin-bottom: 15px;">
                <strong>{{ $submission->exercice->titre }}</strong>
                <p>
                    Statut : 
                    @if($submission->note)
                        ✅ Noté : {{ $submission->note }}/20
                    @else
                        ⏳ En attente de correction
                    @endif
                </p>
                <small>Soumis le : {{ $submission->created_at->format('d/m/Y') }}</small>
            </div>
        @empty
            <p>Aucune soumission pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection