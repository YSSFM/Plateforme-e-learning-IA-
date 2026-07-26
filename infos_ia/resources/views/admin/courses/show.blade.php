@extends('layouts.admin')

@section('title', 'Détails : ' . $course->titre)

@section('content')
<div class="glass block">
    <h3>📚 Détails du cours</h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
        <div>
            <p style="margin-bottom: 10px;"><strong style="color: #1a1a2e;">Titre :</strong> <span style="color: #2d2d44;">{{ $course->titre }}</span></p>
            <p style="margin-bottom: 10px;"><strong style="color: #1a1a2e;">Module :</strong> <span style="color: #2d2d44;">{{ $course->module->titre }}</span></p>
            <p style="margin-bottom: 10px;"><strong style="color: #1a1a2e;">Niveau :</strong> <span style="color: #2d2d44;">{{ $course->module->niveau->code }}</span></p>
            <p style="margin-bottom: 10px;"><strong style="color: #1a1a2e;">Statut :</strong> 
                <span class="badge {{ $course->statut }}">{{ $course->statut }}</span>
            </p>
            <p style="margin-bottom: 10px;"><strong style="color: #1a1a2e;">Ordre :</strong> <span style="color: #2d2d44;">{{ $course->ordre }}</span></p>
            <p style="margin-bottom: 10px;"><strong style="color: #1a1a2e;">Créé le :</strong> <span style="color: #2d2d44;">{{ $course->created_at->format('d/m/Y H:i') }}</span></p>
        </div>
        <div>
            <p><strong style="color: #1a1a2e;">Contenu :</strong></p>
            <div style="background: rgba(0, 0, 0, 0.03); padding: 15px; border-radius: 10px; max-height: 300px; overflow-y: auto; color: #2d2d44;">
                {!! nl2br(e($course->contenu)) !!}
            </div>
        </div>
    </div>
    
    <!-- SECTION FICHIERS -->
    @php
        $fichiers = $course->fichier ? explode(',', $course->fichier) : [];
        $fichiers = array_filter($fichiers);
    @endphp
    
    <div style="margin-top: 25px; padding: 20px; background: rgba(0, 0, 0, 0.03); border-radius: 12px; border: 1px solid rgba(0, 0, 0, 0.06);">
        <h4 style="color: #1a1a2e; margin-bottom: 15px;">📎 Fichiers du cours ({{ count($fichiers) }} fichier(s))</h4>
        
        @if(count($fichiers) > 0)
            <ul style="list-style: none; padding: 0;">
                @foreach($fichiers as $fichier)
                    @if(trim($fichier))
                    <li class="file-item">
                        <span>📄 {{ basename($fichier) }}</span>
                        <a href="{{ asset('storage/courses/' . $fichier) }}" target="_blank" class="btn" style="padding: 6px 18px; font-size: 0.85rem; background: #1cb0f6; color: #ffffff !important;">
                            👁 Voir / Télécharger
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
        @else
            <p style="color: #6c757d; font-style: italic;">Aucun fichier joint à ce cours.</p>
        @endif
    </div>
    
    @if($course->exercices->count() > 0)
    <div style="margin-top: 20px; padding: 20px; background: rgba(88, 204, 2, 0.08); border-radius: 12px; border: 1px solid rgba(88, 204, 2, 0.15);">
        <h4 style="color: #1a1a2e;">✍️ Exercices associés ({{ $course->exercices->count() }})</h4>
        <ul style="list-style: none; padding: 0; margin-top: 10px;">
            @foreach($course->exercices as $exercice)
            <li style="padding: 10px 14px; background: rgba(255,255,255,0.6); border-radius: 8px; margin-bottom: 5px; color: #2d2d44;">
                <strong>{{ $exercice->titre }}</strong> - {{ $exercice->type }} ({{ $exercice->points_max }}/20)
            </li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="admin-actions" style="margin-top: 25px;">
        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn">✏ Modifier</a>
        <a href="{{ route('admin.courses.index') }}" class="btn-outline">← Retour</a>
    </div>
</div>
@endsection