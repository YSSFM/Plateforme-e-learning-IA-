@extends('layouts.user')

@section('title', 'Exercices')

@section('content')
<div class="card">
    <h1>✍️ Exercices</h1>
    <p>Entraînez-vous et soumettez vos réponses.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    @forelse($exercices as $exercice)
    <div class="card">
        <h3>{{ $exercice->titre }}</h3>
        <p><strong>Cours :</strong> {{ $exercice->cours->titre }}</p>
        <p><strong>Module :</strong> {{ $exercice->cours->module->titre }}</p>
        <p><strong>Points max :</strong> {{ $exercice->points_max }}/20</p>
        
        @if(in_array($exercice->id, $submittedIds))
            <span class="badge" style="background: #c8f7dc; color: #146c43;">✅ Déjà soumis</span>
        @else
            <a href="{{ route('user.exercises.show', $exercice->id) }}" class="btn">Faire l'exercice →</a>
        @endif
    </div>
    @empty
    <div class="card">
        <p>Aucun exercice disponible pour le moment.</p>
    </div>
    @endforelse
</div>

<div style="margin-top: 20px;">
    {{ $exercices->links() }}
</div>
@endsection