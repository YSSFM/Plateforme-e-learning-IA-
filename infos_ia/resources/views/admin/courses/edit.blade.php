@extends('layouts.admin')

@section('title', 'Modifier : ' . $course->titre)

@section('content')
<div class="glass block">
    <h3>✏️ Modifier le cours : {{ $course->titre }}</h3>

    <form method="POST" action="{{ route('admin.courses.update', $course->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Module *</label>
        <select name="module_id" required>
            @foreach($modules as $module)
                <option value="{{ $module->id }}" {{ $course->module_id == $module->id ? 'selected' : '' }}>
                    {{ $module->titre }} ({{ $module->niveau->code }})
                </option>
            @endforeach
        </select>

        <label>Titre du cours *</label>
        <input type="text" name="titre" value="{{ $course->titre }}" required>

        <label>Contenu (optionnel - description)</label>
        <textarea name="contenu" rows="5">{{ $course->contenu }}</textarea>

        <label>Fichiers du cours (PDF, DOC, DOCX, ZIP, PPT - max 10 Mo chacun)</label>
        
        @php
            $fichiers = $course->fichier ? explode(',', $course->fichier) : [];
            $fichiers = array_filter($fichiers);
        @endphp
        
        @if(count($fichiers) > 0)
            <div style="background: rgba(0, 150, 255, 0.1); padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                <p style="font-weight: 600; margin-bottom: 10px;">📎 Fichiers actuels :</p>
                @foreach($fichiers as $fichier)
                    @if(trim($fichier))
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: rgba(255,255,255,0.2); border-radius: 8px; margin-bottom: 5px;">
                        <span>📄 {{ basename($fichier) }}</span>
                        <a href="{{ asset('storage/courses/' . $fichier) }}" target="_blank" class="btn-outline" style="font-size: 0.8rem; padding: 4px 12px;">Télécharger</a>
                    </div>
                    @endif
                @endforeach
                <p style="font-size: 0.85rem; opacity: 0.7; margin-top: 10px;">⚠️ Les nouveaux fichiers remplaceront tous les anciens.</p>
            </div>
        @endif

        <div id="file-upload-container">
            <div class="file-upload-group" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                <input type="file" name="fichiers[]" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx" style="flex: 1;">
                <button type="button" class="btn-danger" onclick="removeFileGroup(this)" style="font-size: 0.8rem; padding: 5px 10px;">✖</button>
            </div>
        </div>
        
        <button type="button" onclick="addFileInput()" class="btn-outline" style="margin-bottom: 15px;">➕ Ajouter un autre fichier</button>

        <label>Ordre d'affichage</label>
        <input type="number" name="ordre" value="{{ $course->ordre }}">

        <label>Statut *</label>
        <select name="statut" required>
            <option value="brouillon" {{ $course->statut == 'brouillon' ? 'selected' : '' }}>📝 Brouillon</option>
            <option value="publie" {{ $course->statut == 'publie' ? 'selected' : '' }}>✅ Publié</option>
            <option value="archive" {{ $course->statut == 'archive' ? 'selected' : '' }}>📦 Archivé</option>
        </select>

        <div class="admin-actions">
            <button type="submit" class="btn">💾 Mettre à jour</button>
            <a href="{{ route('admin.courses.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>

<script>
    function addFileInput() {
        const container = document.getElementById('file-upload-container');
        const group = document.createElement('div');
        group.className = 'file-upload-group';
        group.style.cssText = 'display: flex; gap: 10px; align-items: center; margin-bottom: 10px;';
        group.innerHTML = `
            <input type="file" name="fichiers[]" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx" style="flex: 1;">
            <button type="button" class="btn-danger" onclick="removeFileGroup(this)" style="font-size: 0.8rem; padding: 5px 10px;">✖</button>
        `;
        container.appendChild(group);
    }

    function removeFileGroup(button) {
        const container = document.getElementById('file-upload-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        } else {
            alert('Vous devez garder au moins un champ de fichier.');
        }
    }
</script>
@endsection