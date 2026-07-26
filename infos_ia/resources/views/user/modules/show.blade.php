@extends('layouts.user')

@section('title', $module->titre)

@section('content')
<div class="card">
    <h1>{{ $module->titre }}</h1>
    <p>{{ $module->description }}</p>
    <p><strong>Niveau :</strong> {{ $module->niveau->code }}</p>
</div>

<div class="card">
    <h3>📘 Cours de ce module</h3>
    @forelse($module->cours as $course)
    <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.2);">
        <h4>{{ $course->titre }}</h4>
        <p style="opacity: 0.8;">{{ Str::limit($course->contenu, 150) }}</p>
        <a href="{{ route('user.courses.show', $course->id) }}" class="btn-outline">Lire le cours →</a>
    </div>
    @empty
    <p>Aucun cours disponible dans ce module pour le moment.</p>
    @endforelse
</div>
@endsection