@extends('layouts.admin')

@section('title', 'Ajouter un exercice')

@section('content')
<div class="glass block">
    <h3>➕ Ajouter un exercice</h3>

    @if($errors->any())
        <div style="padding: 12px; background: rgba(220,53,69,0.15); border-radius: 10px; margin-bottom: 15px; color: #dc3545;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.exercices.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Cours *</label>
        <select name="cours_id" required>
            <option value="">-- Sélectionner un cours --</option>
            @foreach($cours as $c)
                <option value="{{ $c->id }}">{{ $c->titre }} ({{ $c->module->niveau->code }})</option>
            @endforeach
        </select>

        <label>Titre *</label>
        <input type="text" name="titre" required>

        <label>Énoncé (texte)</label>
        <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 5px;">Rédigez l'énoncé ici, OU téléversez un fichier ci-dessous. Au moins un des deux est requis.</p>
        <textarea name="enonce" rows="8" placeholder="Décrivez l'exercice..."></textarea>

        <label>Fichier énoncé (PDF, DOC, DOCX, PPT, ZIP - max 10 Mo)</label>
        <input type="file" name="fichier_enonce" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">

        <label>Type *</label>
        <select name="type" required>
            <option value="theorique">📖 Théorique</option>
            <option value="pratique">💻 Pratique</option>
            <option value="qcm">📝 QCM</option>
        </select>

        <label>Points maximum</label>
        <input type="number" name="points_max" value="20" min="1" max="20">

        <label>Date limite (optionnelle)</label>
        <input type="datetime-local" name="deadline">

        <label>Correction (texte, optionnelle)</label>
        <textarea name="correction" rows="5" placeholder="Corrigé visible pour l'admin uniquement..."></textarea>

        <label>Fichier correction (optionnel, PDF, DOC, DOCX, PPT, ZIP - max 10 Mo)</label>
        <input type="file" name="fichier_correction" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">

        <div class="admin-actions">
            <button type="submit" class="btn">💾 Enregistrer</button>
            <a href="{{ route('admin.exercices.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection