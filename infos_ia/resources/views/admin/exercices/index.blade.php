@extends('layouts.admin')

@section('title', 'Gestion des exercices')

@section('content')
<div class="glass block">
    <h3 style="color: #1a1a2e;">✍️ Gestion des exercices</h3>

    <div class="admin-actions">
        <a href="{{ route('admin.exercices.create') }}" class="btn">➕ Ajouter un exercice</a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 12px; background: rgba(220, 53, 69, 0.15); border-radius: 10px; margin-bottom: 15px; color: #dc3545; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    @if($exercices->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Cours</th>
                <th>Module</th>
                <th>Type</th>
                <th>Points</th>
                <th>Date limite</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exercices as $exercice)
            <tr @if(!$exercice->cours) style="background: rgba(220,53,69,0.06);" @endif>
                <td>{{ $exercice->titre }}</td>
                <td>
                    @if($exercice->cours)
                        {{ $exercice->cours->titre }}
                    @else
                        <span style="color: #dc3545; font-weight: 600;" title="Le cours de cet exercice a été supprimé">⚠️ Cours supprimé</span>
                    @endif
                </td>
                <td>{{ $exercice->cours?->module?->titre ?? '—' }}</td>
                <td>{{ $exercice->type }}</td>
                <td>{{ $exercice->points_max }}</td>
                <td>{{ $exercice->deadline ? \Carbon\Carbon::parse($exercice->deadline)->format('d/m/Y') : '—' }}</td>
                <td>
                    <a href="{{ route('admin.exercices.show', $exercice->id) }}" class="btn-outline" style="font-size: 0.8rem; padding: 4px 12px;">👁 Voir</a>
                    <a href="{{ route('admin.exercices.edit', $exercice->id) }}" class="btn-outline" style="font-size: 0.8rem; padding: 4px 12px;">✏ Modifier</a>
                    <form method="POST" action="{{ route('admin.exercices.destroy', $exercice->id) }}" style="display: inline;" onsubmit="return confirm('Supprimer cet exercice ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="font-size: 0.8rem; padding: 4px 12px;">🗑 Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $exercices->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: #6c757d;">
        <p style="font-size: 1.1rem;">Aucun exercice pour le moment.</p>
        <a href="{{ route('admin.exercices.create') }}" class="btn" style="margin-top: 15px;">➕ Ajouter un exercice</a>
    </div>
    @endif
</div>
@endsection