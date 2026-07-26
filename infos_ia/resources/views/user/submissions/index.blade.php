@extends('layouts.user')

@section('title', 'Mes soumissions')

@section('content')
<div class="card">
    <h1>📂 Mes soumissions</h1>
    <p>Historique de tous vos travaux soumis.</p>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Exercice</th>
                <th>Cours</th>
                <th>Fichier</th>
                <th>Note</th>
                <th>Feedback / Correction</th>
                <th>Statut</th>
                <th>Soumis le</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $submission)
            <tr>
                <td>{{ $submission->exercice->titre }}</td>
                <td>{{ $submission->exercice->cours->titre }}</td>
                <td>
                    @if($submission->fichier)
                        <a href="{{ asset('storage/submissions/' . $submission->fichier) }}" target="_blank" class="btn-outline">📄 Télécharger</a>
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if($submission->note)
                        {{ $submission->note }}/20
                    @else
                        —
                    @endif
                </td>
                <td style="max-width: 250px;">
                    @if($submission->statut == 'corrige' && $submission->feedback)
                        {{ Str::limit($submission->feedback, 80) }}
                    @elseif($submission->statut == 'corrige')
                        <span style="opacity: 0.6;">Aucun commentaire</span>
                    @else
                        <span style="opacity: 0.6;">En attente de correction</span>
                    @endif
                </td>
                <td>
                    @if($submission->statut == 'corrige')
                        ✅ Corrigé
                    @else
                        ⏳ En attente
                    @endif
                </td>
                <td>{{ $submission->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="7">Aucune soumission effectuée.</td></tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $submissions->links() }}
    </div>
</div>
@endsection