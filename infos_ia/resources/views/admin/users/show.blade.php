@extends('layouts.admin')

@section('title', 'Utilisateur : ' . $user->username)

@section('content')
<div class="glass block">
    <h3>👤 {{ $user->username }}</h3>

    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Niveau :</strong> {{ $user->niveau ? $user->niveau->code : 'Non défini' }}</p>
    <p><strong>Statut :</strong> <span class="badge {{ $user->statut }}">{{ $user->statut }}</span></p>
    <p><strong>Inscrit le :</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>

    <div class="admin-actions">
        @if($user->statut === 'actif')
            <form method="POST" action="{{ route('admin.users.block', $user->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-danger">🔒 Bloquer</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.users.unblock', $user->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-outline">🔓 Débloquer</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;" onsubmit="return confirm('Supprimer cet utilisateur ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">🗑 Supprimer</button>
        </form>
        <a href="{{ route('admin.users.index') }}" class="btn-outline">← Retour</a>
    </div>
</div>

<div class="glass block" style="margin-top: 20px;">
    <h4>Historique des soumissions</h4>
    <table>
        <thead>
            <tr><th>Exercice</th><th>Fichier</th><th>Note</th><th>Statut</th><th>Soumis le</th></tr>
        </thead>
        <tbody>
            @forelse($user->soumissions as $sub)
            <tr>
                <td>{{ $sub->exercice->titre ?? 'N/A' }}</td>
                <td>
                    @if($sub->fichier)
                        <a href="{{ asset('storage/submissions/' . $sub->fichier) }}" target="_blank">📄 Voir</a>
                    @else
                        —
                    @endif
                </td>
                <td>{{ $sub->note ?? 'Non noté' }}</td>
                <td>{{ $sub->statut }}</td>
                <td>{{ $sub->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5">Aucune soumission</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection