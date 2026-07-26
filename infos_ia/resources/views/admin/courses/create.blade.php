@extends('layouts.admin')

@section('title', 'Ajouter un cours')

@section('content')
<style>
    .form-section {
        margin-bottom: 28px;
        padding-bottom: 28px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .form-section:last-of-type {
        border-bottom: none;
    }
    .form-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #2d7d00;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 16px;
    }
    .form-hint {
        font-size: 0.85rem;
        color: #6c7280;
        margin-top: -10px;
        margin-bottom: 14px;
    }
    .dropzone {
        border: 2px dashed rgba(88, 204, 2, 0.4);
        border-radius: 14px;
        background: rgba(88, 204, 2, 0.05);
        padding: 20px;
        transition: all 0.2s;
    }
    .dropzone:hover {
        border-color: #58cc02;
        background: rgba(88, 204, 2, 0.08);
    }
    .file-row {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }
    .file-row input[type="file"] {
        margin-bottom: 0;
        flex: 1;
        background: #fff;
    }
    .remove-file-btn {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .remove-file-btn:hover {
        background: #dc3545;
        color: #fff;
    }
    .add-file-btn {
        background: none;
        border: 2px dashed rgba(88, 204, 2, 0.5);
        color: #2d7d00;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .add-file-btn:hover {
        background: rgba(88, 204, 2, 0.1);
        border-color: #58cc02;
    }
    .status-choice {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }
    .status-option {
        position: relative;
    }
    .status-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
        z-index: 2;
    }
    .status-card {
        border: 2px solid rgba(0,0,0,0.1);
        border-radius: 14px;
        padding: 16px 12px;
        text-align: center;
        font-weight: 600;
        color: #2d2d44;
        transition: all 0.2s;
        background: #fff;
    }
    .status-option input[type="radio"]:checked + .status-card {
        border-color: #58cc02;
        background: rgba(88, 204, 2, 0.1);
        color: #2d7d00;
        box-shadow: 0 4px 12px rgba(88, 204, 2, 0.2);
    }
    .status-option input[type="radio"]:checked + .status-card .status-emoji {
        transform: scale(1.15);
    }
    .status-emoji {
        display: block;
        font-size: 1.6rem;
        margin-bottom: 6px;
        transition: transform 0.2s;
    }
    .status-warning {
        font-size: 0.85rem;
        color: #856404;
        background: rgba(255, 193, 7, 0.15);
        border-left: 3px solid #f59e0b;
        padding: 10px 14px;
        border-radius: 8px;
        margin-top: 14px;
        display: none;
    }
    .status-warning.visible {
        display: block;
    }
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .page-header h3 {
        font-size: 1.4rem;
    }
</style>

<div class="glass block" style="max-width: 780px; margin: 0 auto;">
    <div class="page-header">
        <h3>➕ Ajouter un cours</h3>
        <a href="{{ route('admin.courses.index') }}" class="btn-outline">← Retour</a>
    </div>
    <p style="color: #6c7280; margin-bottom: 24px;">Renseignez les informations ci-dessous pour publier un nouveau cours.</p>

    @if($errors->any())
        <div class="alert-error">
            <strong>⚠️ Impossible d'enregistrer ce cours :</strong>
            <ul style="margin: 8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-section">
            <div class="form-section-title">📦 Rattachement</div>

            <label>Module *</label>
            <select name="module_id" required>
                <option value="">-- Sélectionner un module --</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                        {{ $module->titre }} ({{ $module->niveau->code ?? '—' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-section">
            <div class="form-section-title">📝 Contenu</div>

            <label>Titre du cours *</label>
            <input type="text" name="titre" value="{{ old('titre') }}" placeholder="Ex : Applications linéaires" required>

            <label>Description (optionnelle)</label>
            <textarea name="contenu" rows="4" placeholder="Brève description du cours...">{{ old('contenu', 'Ce cours est disponible en téléchargement.') }}</textarea>

            <label>Ordre d'affichage</label>
            <input type="number" name="ordre" value="{{ old('ordre', 1) }}" style="max-width: 140px;">
        </div>

        <div class="form-section">
            <div class="form-section-title">📎 Fichiers</div>
            <p class="form-hint">PDF, DOC, DOCX, ZIP, PPT — 10 Mo max par fichier.</p>

            <div class="dropzone">
                <div id="file-upload-container">
                    <div class="file-row">
                        <input type="file" name="fichiers[]" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx">
                        <button type="button" class="remove-file-btn" onclick="removeFileGroup(this)">✖</button>
                    </div>
                </div>
                <button type="button" class="add-file-btn" onclick="addFileInput()">➕ Ajouter un autre fichier</button>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">🚦 Statut de publication</div>

            <div class="status-choice">
                <label class="status-option">
                    <input type="radio" name="statut" value="publie" checked onclick="toggleStatusWarning()">
                    <div class="status-card"><span class="status-emoji">✅</span>Publié</div>
                </label>
                <label class="status-option">
                    <input type="radio" name="statut" value="brouillon" onclick="toggleStatusWarning()">
                    <div class="status-card"><span class="status-emoji">📝</span>Brouillon</div>
                </label>
                <label class="status-option">
                    <input type="radio" name="statut" value="archive" onclick="toggleStatusWarning()">
                    <div class="status-card"><span class="status-emoji">📦</span>Archivé</div>
                </label>
            </div>

            <div class="status-warning" id="status-warning">
                ⚠️ Ce cours ne sera <strong>pas visible</strong> par les étudiants tant qu'il n'est pas passé en "Publié".
            </div>
        </div>

        <div class="admin-actions">
            <button type="submit" class="btn">💾 Enregistrer le cours</button>
            <a href="{{ route('admin.courses.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>

<script>
    function addFileInput() {
        const container = document.getElementById('file-upload-container');
        const row = document.createElement('div');
        row.className = 'file-row';
        row.innerHTML = `
            <input type="file" name="fichiers[]" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx">
            <button type="button" class="remove-file-btn" onclick="removeFileGroup(this)">✖</button>
        `;
        container.appendChild(row);
    }

    function removeFileGroup(button) {
        const container = document.getElementById('file-upload-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        } else {
            alert('Vous devez garder au moins un champ de fichier.');
        }
    }

    function toggleStatusWarning() {
        const selected = document.querySelector('input[name="statut"]:checked').value;
        document.getElementById('status-warning').classList.toggle('visible', selected !== 'publie');
    }
</script>
@endsection