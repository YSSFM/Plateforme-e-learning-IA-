@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="glass block">
    <h1>Tableau de bord</h1>
    <p>Bienvenue sur votre espace d'administration, {{ Auth::user()->username }}.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
    <div class="glass block" style="text-align: center;">
        <h2 style="font-size: 3rem;">{{ $totalUsers }}</h2>
        <p>👥 Utilisateurs</p>
    </div>
    <div class="glass block" style="text-align: center;">
        <h2 style="font-size: 3rem;">{{ $totalCourses }}</h2>
        <p>Cours</p>
    </div>
    <div class="glass block" style="text-align: center;">
        <h2 style="font-size: 3rem;">{{ $totalModules }}</h2>
        <p>📦 Modules</p>
    </div>
    <div class="glass block" style="text-align: center;">
        <h2 style="font-size: 3rem;">{{ $pendingSubmissions }}</h2>
        <p>⏳ Soumissions en attente</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
    <div class="glass block">
        <h3>Derniers utilisateurs</h3>
        <table>
            <thead>
                <tr><th>Nom</th><th>Email</th><th>Statut</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($recentUsers as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge {{ $user->statut }}">{{ $user->statut }}</span></td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4">Aucun utilisateur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="glass block">
        <h3>Dernières soumissions</h3>
        <table>
            <thead>
                <tr><th>Étudiant</th><th>Exercice</th><th>Statut</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($recentSubmissions as $sub)
                <tr>
                    <td>{{ $sub->user->username ?? '—' }}</td>
                    <td>
                        @if($sub->exercice)
                            {{ $sub->exercice->titre }}
                        @else
                            <span style="color: #dc3545;" title="Exercice supprimé">⚠️ Exercice supprimé</span>
                        @endif
                    </td>
                    <td>{{ $sub->statut }}</td>
                    <td>{{ $sub->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4">Aucune soumission.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection