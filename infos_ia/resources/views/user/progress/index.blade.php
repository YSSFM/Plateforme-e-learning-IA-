@extends('layouts.user')

@section('title', 'Ma progression')

@section('content')
<div class="card" style="text-align: center;">
    <h1>📊 Ma progression</h1>
    
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px;">
        <div class="card" style="background: rgba(88, 204, 2, 0.15);">
            <div style="font-size: 3rem; font-weight: bold; color: #58cc02;">{{ $percentage }}%</div>
            <p>Progression globale</p>
        </div>
        <div class="card" style="background: rgba(0, 150, 255, 0.15);">
            <div style="font-size: 3rem; font-weight: bold; color: #1cb0f6;">{{ $completedCourses }}</div>
            <p>Cours terminés</p>
        </div>
        <div class="card" style="background: rgba(255, 193, 7, 0.15);">
            <div style="font-size: 3rem; font-weight: bold; color: #f59e0b;">{{ $inProgressCourses }}</div>
            <p>Cours en cours</p>
        </div>
    </div>
    
    <div style="margin-top: 20px; background: rgba(255,255,255,0.2); padding: 20px; border-radius: 10px;">
        <div style="height: 30px; background: rgba(255,255,255,0.3); border-radius: 15px; overflow: hidden;">
            <div style="height: 100%; width: {{ $percentage }}%; background: linear-gradient(90deg, #58cc02, #1cb0f6); border-radius: 15px; transition: width 0.5s;"></div>
        </div>
        <p style="margin-top: 10px;">
            {{ $completedCourses }} / {{ $totalCourses }} cours terminés
            @if($notStartedCourses > 0)
                ({{ $notStartedCourses }} non commencés)
            @endif
        </p>
    </div>
</div>

<div class="card">
    <h3>📘 Détail par cours</h3>
    <p style="opacity: 0.7; margin-bottom: 15px;">La progression est automatiquement calculée en fonction des exercices soumis.</p>
    
    @if($progressions->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Cours</th>
                <th>Module</th>
                <th>Niveau</th>
                <th>Statut</th>
                <th>Progression</th>
            </tr>
        </thead>
        <tbody>
            @foreach($progressions as $progression)
                @continue(!$progression->cours || !$progression->cours->module)
            <tr>
                <td>{{ $progression->cours->titre }}</td>
                <td>{{ $progression->cours->module->titre }}</td>
                <td>{{ $progression->cours->module->niveau?->code ?? '—' }}</td>
                <td>
                    @if($progression->statut == 'termine')
                        <span class="badge" style="background: #c8f7dc; color: #146c43;">✅ Terminé</span>
                    @elseif($progression->statut == 'en_cours')
                        <span class="badge" style="background: #fff3cd; color: #856404;">🔄 En cours</span>
                    @else
                        <span class="badge" style="background: #e9ecef; color: #6c757d;">⚪ Non commencé</span>
                    @endif
                </td>
                <td>
                    @php
                        // Calcul du pourcentage de progression pour ce cours
                        $exercicesCount = \App\Models\Exercice::where('cours_id', $progression->cours_id)->count();
                        $soumisCount = \App\Models\Soumission::where('user_id', auth()->id())
                            ->whereIn('exercice_id', \App\Models\Exercice::where('cours_id', $progression->cours_id)->pluck('id'))
                            ->count();
                        $progress = $exercicesCount > 0 ? round(($soumisCount / $exercicesCount) * 100) : 0;
                    @endphp
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="flex: 1; height: 8px; background: rgba(255,255,255,0.3); border-radius: 4px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $progress }}%; background: linear-gradient(90deg, #58cc02, #1cb0f6); border-radius: 4px;"></div>
                        </div>
                        <span style="font-size: 0.85rem; min-width: 40px;">{{ $progress }}%</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>Aucune progression enregistrée. Commencez un cours pour suivre votre progression !</p>
    @endif
</div>

@if($notStartedCourses > 0)
<div class="card" style="background: rgba(255, 193, 7, 0.1);">
    <h3>💡 Cours à commencer</h3>
    <p>Vous avez {{ $notStartedCourses }} cours non commencés. Visitez les modules pour les découvrir !</p>
    <a href="{{ route('user.modules.index') }}" class="btn">Voir les modules →</a>
</div>
@endif
@endsection