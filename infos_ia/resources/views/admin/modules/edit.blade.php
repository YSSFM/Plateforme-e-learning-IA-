@extends('layouts.admin')

@section('title', 'Modifier : ' . $module->titre)

@section('content')
<div class="glass block">
    <h3>✏️ Modifier le module : {{ $module->titre }}</h3>

    <form method="POST" action="{{ route('admin.modules.update', $module->id) }}">
        @csrf @method('PUT')

        <label>Titre *</label>
        <input type="text" name="titre" value="{{ $module->titre }}" required>

        <label>Description</label>
        <textarea name="description" rows="4">{{ $module->description }}</textarea>

        <label>Niveau *</label>
        <select name="niveau_id" required>
            @foreach($niveaux as $niveau)
                <option value="{{ $niveau->id }}" {{ $module->niveau_id == $niveau->id ? 'selected' : '' }}>
                    {{ $niveau->code }} - {{ $niveau->libelle }}
                </option>
            @endforeach
        </select>

        <label>Ordre d'affichage</label>
        <input type="number" name="ordre" value="{{ $module->ordre }}">

        <div class="admin-actions">
            <button type="submit" class="btn">💾 Mettre à jour</button>
            <a href="{{ route('admin.modules.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection