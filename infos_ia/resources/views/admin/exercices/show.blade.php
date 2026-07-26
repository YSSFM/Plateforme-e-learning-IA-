@extends('layouts.admin')

@section('title', 'Détails : ' . $exercice->titre)

@section('content')
<div class="glass block">
    <h3>📝 Détails de l'exercice</h3>
    
    <p><strong>Titre :</strong> {{ $exercice->titre }}</p>
    <p><strong>Cours :</strong> {{ $exercice->cours->titre }}</p>
    <p><strong>Module :</strong> {{ $exercice->cours->module->titre ?? '—' }}</p>
    <p><strong>Type :</strong> {{ $exercice->type }}</p>
    <p><strong>Points :</strong> {{ $exercice->points_max }}/20</p>
    <p><strong>Date limite :</strong> {{ $exercice->deadline ? $exercice->deadline->format('d/m/Y H:i') : 'Aucune' }}</p>
    
    @if($exercice->enonce)
    <h4 style="margin-top: 20px;">📄 Énoncé</h4>
    <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 10px;">
        {!! nl2br(e($exercice->enonce)) !!}
    </div>
    @endif

    @if($exercice->fichier_enonce)
    <h4 style="margin-top: 20px;">📎 Fichier énoncé</h4>
    <a href="{{ asset('storage/exercices/' . $exercice->fichier_enonce) }}" target="_blank" class="btn-outline">📄 Télécharger</a>
    @endif
    
    @if($exercice->correction)
    <h4 style="margin-top: 20px;">✅ Correction</h4>
    <div style="background: rgba(88, 204, 2, 0.1); padding: 15px; border-radius: 10px;">
        {!! nl2br(e($exercice->correction)) !!}
    </div>
    @endif

    @if($exercice->fichier_correction)
    <h4 style="margin-top: 20px;">📎 Fichier correction</h4>
    <a href="{{ asset('storage/exercices/corrections/' . $exercice->fichier_correction) }}" target="_blank" class="btn-outline">📄 Télécharger</a>
    @endif

    <div class="admin-actions" style="margin-top: 20px;">
        <a href="{{ route('admin.exercices.edit', $exercice->id) }}" class="btn">✏ Modifier</a>
        <a href="{{ route('admin.exercices.index') }}" class="btn-outline">← Retour</a>
    </div>
</div>
@endsection