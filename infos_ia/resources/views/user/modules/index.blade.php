@extends('layouts.user')

@section('title', 'Modules')

@section('content')
<div class="card">
    <h1>Modules disponibles</h1>
    <p>Découvrez les modules adaptés à votre niveau.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px;">
    @forelse($modules as $module)
    <div class="card">
        <h3>{{ $module->titre }}</h3>
        <p style="opacity: 0.85; margin: 10px 0;">{{ Str::limit($module->description, 120) }}</p>
        <p><strong>Niveau :</strong> {{ $module->niveau->code }}</p>
        <a href="{{ route('user.modules.show', $module->id) }}" class="btn" style="margin-top: 10px;">Voir le contenu →</a>
    </div>
    @empty
    <div class="card">
        <p>Aucun module disponible pour le moment.</p>
    </div>
    @endforelse
</div>

<div style="margin-top: 20px;">
    {{ $modules->links() }}
</div>
@endsection