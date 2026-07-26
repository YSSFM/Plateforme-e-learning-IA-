@extends('layouts.user')

@section('title', $course->titre)

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
        <div>
            <h1>{{ $course->titre }}</h1>
            <p style="opacity: 0.85;"><strong>Module :</strong> {{ $course->module->titre ?? '—' }}</p>
        </div>
        <a href="{{ route('user.modules.show', $course->module_id) }}" class="btn-outline">← Retour au module</a>
    </div>
</div>

<div class="card">
    <h3>📖 Contenu du cours</h3>
    <div style="line-height: 1.7;">
        {!! nl2br(e($course->contenu)) !!}
    </div>
</div>

@php
    $fichiers = $course->fichier ? array_filter(explode(',', $course->fichier)) : [];
@endphp

@if(count($fichiers) > 0)
<div class="card">
    <h3>📎 Documents du cours</h3>
    <div class="file-list">
        @foreach($fichiers as $fichier)
            <a href="{{ asset('storage/courses/' . $fichier) }}" target="_blank">
                📄 {{ $fichier }}
            </a>
        @endforeach
    </div>
</div>
@endif

@if($course->ressources->count())
<div class="card">
    <h3>📂 Ressources complémentaires</h3>
    <div class="file-list">
        @foreach($course->ressources as $ressource)
            <a href="{{ $ressource->url }}" target="_blank">
                🔗 {{ $ressource->titre ?? $ressource->url }} <span style="opacity:0.7; font-weight: 400;">({{ $ressource->type }})</span>
            </a>
        @endforeach
    </div>
</div>
@endif

@if($course->exercices->count())
<div class="card">
    <h3>✍️ Exercices associés</h3>
    <div style="display: flex; flex-direction: column; gap: 10px;">
        @foreach($course->exercices as $exercice)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; background: rgba(255,255,255,0.35); border-radius: 12px;">
            <strong>{{ $exercice->titre }}</strong>
            <a href="{{ route('user.exercises.show', $exercice->id) }}" class="btn-outline">Faire l'exercice →</a>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card">
    <h3>📊 Ma progression</h3>
    <p style="margin-bottom: 16px;">Statut actuel :
        <strong>
            @if($progression->statut == 'non_commence')
                ⚪ Non commencé
            @elseif($progression->statut == 'en_cours')
                🔄 En cours
            @else
                ✅ Terminé
            @endif
        </strong>
    </p>
    
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <select id="progressStatus" style="width: auto; margin-bottom: 0;">
            <option value="non_commence" {{ $progression->statut == 'non_commence' ? 'selected' : '' }}>⚪ Non commencé</option>
            <option value="en_cours" {{ $progression->statut == 'en_cours' ? 'selected' : '' }}>🔄 En cours</option>
            <option value="termine" {{ $progression->statut == 'termine' ? 'selected' : '' }}>✅ Terminé</option>
        </select>
        <button onclick="updateProgress()" class="btn-outline">Mettre à jour</button>
    </div>
</div>

<script>
function updateProgress() {
    const status = document.getElementById('progressStatus').value;
    fetch('{{ route("user.courses.progress", $course->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ statut: status })
    }).then(response => response.json())
      .then(data => {
          if(data.success) {
              alert('Progression mise à jour !');
          }
      });
}
</script>
@endsection