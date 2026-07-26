@extends('layouts.admin')

@section('title', 'Ajouter un module')

@section('content')
<div class="glass block">
    <h3>➕ Ajouter un module</h3>

    <form method="POST" action="{{ route('admin.modules.store') }}">
        @csrf

        <label>Titre *</label>
        <input type="text" name="titre" required>

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <label>Niveau *</label>
        <select name="niveau_id" required>
            <option value="">-- Sélectionner --</option>
            @foreach($niveaux as $niveau)
                <option value="{{ $niveau->id }}">{{ $niveau->code }} - {{ $niveau->libelle }}</option>
            @endforeach
        </select>

        <label>Ordre d'affichage</label>
        <input type="number" name="ordre" value="1">

        <div class="admin-actions">
            <button type="submit" class="btn">💾 Enregistrer</button>
            <a href="{{ route('admin.modules.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection