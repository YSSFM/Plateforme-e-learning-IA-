@extends('layouts.admin')

@section('title', 'Remarques administratives')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Formulaire d'ajout -->
    <div class="glass block">
        <h3>➕ Ajouter une remarque</h3>
        <form method="POST" action="{{ route('admin.remarks.store') }}">
            @csrf

            <label>Utilisateur *</label>
            <select name="user_id" required>
                <option value="">-- Sélectionner --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                @endforeach
            </select>

            <label>Type</label>
            <select name="type">
                <option value="info">ℹ️ Information</option>
                <option value="avertissement">⚠️ Avertissement</option>
            </select>

            <label>Message *</label>
            <textarea name="message" rows="4" required></textarea>

            <button type="submit" class="btn">📝 Enregistrer</button>
        </form>
    </div>

    <!-- Liste des remarques -->
    <div class="glass block">
        <h3>Historique des remarques</h3>
        
        @forelse($remarks as $remark)
        <div style="padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.2);">
            <div style="display: flex; justify-content: space-between;">
                <strong>{{ $remark->user->username }}</strong>
                <small>{{ $remark->created_at->format('d/m/Y H:i') }}</small>
            </div>
            <div style="margin: 10px 0;">
                <span class="badge" style="background: {{ $remark->type == 'avertissement' ? '#f8d7da' : '#c8f7dc' }};">
                    {{ $remark->type == 'avertissement' ? '⚠️ Avertissement' : 'ℹ️ Info' }}
                </span>
            </div>
            <p>{{ $remark->message }}</p>
            <small>Par : {{ $remark->admin->username }}</small>
            
            <form method="POST" action="{{ route('admin.remarks.destroy', $remark->id) }}" style="margin-top: 10px;" onsubmit="return confirm('Supprimer cette remarque ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" style="padding: 5px 10px; font-size: 0.8rem;">🗑 Supprimer</button>
            </form>
        </div>
        @empty
        <p>Aucune remarque pour le moment.</p>
        @endforelse

        <div style="margin-top: 20px;">
            {{ $remarks->links() }}
        </div>
    </div>
</div>
@endsection