@extends('layouts.user')

@section('title', $exercice->titre)

@section('content')
<div class="card">
    <h1>{{ $exercice->titre }}</h1>
    <p><strong>Cours :</strong> {{ $exercice->cours->titre }}</p>
    <p><strong>Points max :</strong> {{ $exercice->points_max }}/20</p>
    @if($exercice->deadline)
        <p><strong>Date limite :</strong> {{ \Carbon\Carbon::parse($exercice->deadline)->format('d/m/Y H:i') }}
            @if($deadlinePassed)
                <span style="color: #842029; font-weight: 700;"> — Dépassée</span>
            @endif
        </p>
    @endif
</div>

<div class="card">
    <h3>📄 Énoncé</h3>
    @if($exercice->enonce)
    <div style="line-height: 1.7; margin-bottom: {{ $exercice->fichier_enonce ? '16px' : '0' }};">
        {!! nl2br(e($exercice->enonce)) !!}
    </div>
    @endif
    @if($exercice->fichier_enonce)
        <div class="file-list">
            <a href="{{ asset('storage/exercices/' . $exercice->fichier_enonce) }}" target="_blank">📎 Télécharger l'énoncé</a>
        </div>
    @endif
</div>

@if($submission && $submission->statut == 'corrige')
<div class="card" style="background: rgba(255, 255, 255, 0.55);">
    <h3>✅ Correction reçue</h3>
    <p style="margin-bottom: 8px;"><strong>Note :</strong> <span class="badge corrige">{{ $submission->note }}/20</span></p>
    <p style="margin-bottom: {{ ($exercice->correction || $exercice->fichier_correction) ? '16px' : '0' }};">
        <strong>Feedback :</strong> {{ $submission->feedback ?? 'Aucun commentaire' }}
    </p>

    @if($exercice->correction)
        <div style="background: rgba(255,255,255,0.4); padding: 14px 18px; border-radius: 10px; line-height: 1.6; margin-bottom: {{ $exercice->fichier_correction ? '12px' : '0' }};">
            {!! nl2br(e($exercice->correction)) !!}
        </div>
    @endif

    @if($exercice->fichier_correction)
        <div class="file-list">
            <a href="{{ asset('storage/exercices/corrections/' . $exercice->fichier_correction) }}" target="_blank">📎 Télécharger le corrigé</a>
        </div>
    @endif
</div>
@endif

@if($submission)
<div class="card">
    <h3>📤 Votre soumission</h3>
    <div class="file-list" style="margin-bottom: 12px;">
        <a href="{{ asset('storage/submissions/' . $submission->fichier) }}" target="_blank">📄 {{ $submission->fichier }}</a>
    </div>
    <p>
        Statut :
        @if($submission->statut == 'corrige')
            <span class="badge corrige">✅ Corrigé</span>
        @else
            <span class="badge attente">⏳ En attente de correction</span>
        @endif
    </p>

    @if($canWithdraw)
        <form method="POST" action="{{ route('user.submissions.withdraw', $submission->id) }}" onsubmit="return confirm('Retirer votre soumission ? Vous pourrez la resoumettre avant la date limite.')" style="margin-top: 14px;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">🗑 Retirer ma soumission</button>
        </form>
    @elseif($deadlinePassed)
        <p style="opacity: 0.8; margin-top: 14px;">⛔ La date limite est dépassée, vous ne pouvez plus retirer cette soumission.</p>
    @endif
</div>
@endif

@if(!$submission && !$deadlinePassed)
<div class="card">
    <h3>📤 Soumettre ma réponse</h3>
    
    @if($isLastDay)
        <p style="color: #856404; font-weight: 700; margin-bottom: 14px;">⚠️ C'est le dernier jour pour soumettre ce devoir.</p>
    @endif

    <form method="POST" action="{{ route('user.exercises.submit', $exercice->id) }}" enctype="multipart/form-data"
        @if($isLastDay) onsubmit="return confirm('Es-tu sûr de vouloir rendre ce devoir ? Tu ne pourras plus le retirer après cette soumission.')" @endif>
        @csrf
        
        <label>Fichier (PDF, DOC, DOCX, ZIP - max 5 Mo)</label>
        <input type="file" name="fichier" required accept=".pdf,.doc,.docx,.zip">
        
        <button type="submit" class="btn">Soumettre</button>
    </form>
</div>
@elseif(!$submission && $deadlinePassed)
<div class="card" style="background: rgba(255,255,255,0.5);">
    <p style="color: #842029; font-weight: 600;">⛔ La date limite est dépassée, vous ne pouvez plus soumettre ce devoir.</p>
</div>
@endif

<a href="{{ route('user.exercises.index') }}" class="btn-outline">← Retour aux exercices</a>
@endsection