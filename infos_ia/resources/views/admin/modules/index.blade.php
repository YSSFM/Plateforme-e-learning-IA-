@extends('layouts.admin')

@section('title', 'Gestion des modules')

@section('content')
<div class="glass block">
    <h3>📦 Gestion des modules</h3>

    <div class="admin-actions">
        <a href="{{ route('admin.modules.create') }}" class="btn">➕ Ajouter un module</a>
    </div>

    <table>
        <thead>
            <tr><th>ID</th><th>Titre</th><th>Niveau</th><th>Ordre</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($modules as $module)
            <tr>
                <td>{{ $module->id }}</td>
                <td>{{ $module->titre }}</td>
                <td>{{ $module->niveau->code }}</td>
                <td>{{ $module->ordre }}</td>
                <td>
                    <a href="{{ route('admin.modules.edit', $module->id) }}" class="btn-outline">✏ Modifier</a>
                    <form method="POST" action="{{ route('admin.modules.destroy', $module->id) }}" style="display: inline;" onsubmit="return confirm('Supprimer ce module ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger">🗑 Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $modules->links() }}
</div>
@endsection