@extends('layouts.admin')

@section('title', 'Correction : ' . $submission->exercice->titre)

@section('content')
<div class="glass block" style="max-width: 700px; margin: 0 auto;">
    <h3>✏️ Correction du devoir</h3>

    @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p><strong>Étudiant :</strong> {{ $submission->user->username }}</p>
    <p><strong>Exercice :</strong> {{ $submission->exercice->titre }}</p>

    @if($submission->fichier)
        <p><strong>Fichier soumis :</strong> 
            <a href="{{ asset('storage/submissions/' . $submission->fichier) }}" target="_blank" class="btn-outline">📄 Télécharger</a>
        </p>
    @endif

    <hr style="margin: 20px 0;">

    <form method="POST" action="{{ route('admin.submissions.grade.store', $submission->id) }}" id="grade-form">
        @csrf

        <label>Note (sur 20)</label>
        <input type="number" name="note" min="0" max="20" step="0.5" value="{{ old('note', $submission->note) }}">

        <label>Feedback / Commentaires</label>
        <textarea name="feedback" rows="6">{{ old('feedback', $submission->feedback) }}</textarea>

        <div class="admin-actions">
            <button type="submit" class="btn" id="grade-submit-btn">💾 Enregistrer la correction</button>
            <a href="{{ route('admin.submissions.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('grade-form').addEventListener('submit', function() {
        const btn = document.getElementById('grade-submit-btn');
        btn.disabled = true;
        btn.textContent = '⏳ Enregistrement en cours...';
    });
</script>
@endsection