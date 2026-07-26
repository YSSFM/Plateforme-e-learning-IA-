@extends('layouts.admin')

@section('title', 'Modifier : ' . $exercice->titre)

@section('content')
<div class="glass block">
    <h3>✏️ Modifier l'exercice : {{ $exercice->titre }}</h3>

    @if($errors->any())
        <div style="padding: 12px; background: rgba(220,53,69,0.15); border-radius: 10px; margin-bottom: 15px; color: #dc3545;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.exercices.update', $exercice->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <label>Cours *</label>
        <select name="cours_id" required>
            @foreach($cours as $c)
                <option value="{{ $c->id }}" {{ $exercice->cours_id == $c->id ? 'selected' : '' }}>
                    {{ $c->titre }} ({{ $c->module->niveau->code }})
                </option>
            @endforeach
        </select>

        <label>Titre *</label>
        <input type="text" name="titre" value="{{ $exercice->titre }}" required>

        <label>Énoncé (texte)</label>
        <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 5px;">Laissez vide si vous fournissez uniquement un fichier.</p>
        <textarea name="enonce" rows="8">{{ $exercice->enonce }}</textarea>

        <label>Fichier énoncé actuel</label>
        @if($exercice->fichier_enonce)
            <p><a href="{{ asset('storage/exercices/' . $exercice->fichier_enonce) }}" target="_blank">📄 {{ $exercice->fichier_enonce }}</a></p>
        @else
            <p style="opacity: 0.6;">Aucun fichier.</p>
        @endif
        <label>Remplacer le fichier énoncé (optionnel)</label>
        <input type="file" name="fichier_enonce" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">

        <label>Type *</label>
        <select name="type" required>
            <option value="theorique" {{ $exercice->type == 'theorique' ? 'selected' : '' }}>📖 Théorique</option>
            <option value="pratique" {{ $exercice->type == 'pratique' ? 'selected' : '' }}>💻 Pratique</option>
            <option value="qcm" {{ $exercice->type == 'qcm' ? 'selected' : '' }}>📝 QCM</option>
        </select>

        <label>Points maximum</label>
        <input type="number" name="points_max" value="{{ $exercice->points_max }}" min="1" max="20">

        <label>Date limite</label>
        <input type="datetime-local" name="deadline" value="{{ $exercice->deadline ? $exercice->deadline->format('Y-m-d\TH:i') : '' }}">

        <label>Correction (texte, optionnelle)</label>
        <textarea name="correction" rows="5">{{ $exercice->correction }}</textarea>

        <label>Fichier correction actuel</label>
        @if($exercice->fichier_correction)
            <p><a href="{{ asset('storage/exercices/corrections/' . $exercice->fichier_correction) }}" target="_blank">📄 {{ $exercice->fichier_correction }}</a></p>
        @else
            <p style="opacity: 0.6;">Aucun fichier.</p>
        @endif
        <label>Remplacer le fichier correction (optionnel)</label>
        <input type="file" name="fichier_correction" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip">

        <div class="admin-actions">
            <button type="submit" class="btn">💾 Mettre à jour</button>
            <a href="{{ route('admin.exercices.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection