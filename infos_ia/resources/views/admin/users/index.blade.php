@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="glass block">
    <h3>👥 Gestion des utilisateurs</h3>

    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" style="width: 300px; display: inline-block;">
        <button type="submit" class="btn">🔍 Rechercher</button>
    </form>

    <form method="POST" action="{{ route('admin.users.bulk-action') }}">
        @csrf
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Niveau</th>
                    <th>Statut</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td><input type="checkbox" name="users[]" value="{{ $user->id }}" class="user-checkbox"></td>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->niveau ? $user->niveau->code : 'Non défini' }}</td>
                    <td><span class="badge {{ $user->statut }}">{{ $user->statut }}</span></td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn-outline">👁 Voir</a>
                        @if($user->statut === 'actif')
                            <form method="POST" action="{{ route('admin.users.block', $user->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-outline" style="border-color: #dc3545; color: #dc3545;">🔒 Bloquer</button>
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
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="admin-actions">
            <button type="submit" name="action" value="block" class="btn-outline">🔒 Bloquer sélectionnés</button>
            <button type="submit" name="action" value="unblock" class="btn-outline">🔓 Débloquer sélectionnés</button>
            <button type="submit" name="action" value="delete" class="btn-danger" onclick="return confirm('Supprimer les utilisateurs sélectionnés ?')">🗑 Supprimer sélectionnés</button>
        </div>
    </form>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection