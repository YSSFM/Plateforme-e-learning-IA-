@extends('layouts.user')

@section('title', 'Mon profil')

@section('content')
<div class="card">
    <h1>👤 Mon profil</h1>
    <p>Gérez vos informations personnelles et votre niveau d'étude.</p>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Informations personnelles -->
    <div class="card">
        <h3>📋 Informations personnelles</h3>
        
        <form method="POST" action="{{ route('user.profile.update') }}">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Nom d'utilisateur *</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.3);">
                @error('username')
                    <p style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.3);">
                @error('email')
                    <p style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>
            
            <hr style="margin: 20px 0; border: none; border-top: 1px solid rgba(255,255,255,0.2);">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Nouveau mot de passe</label>
                <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 5px;">Laissez vide pour ne pas modifier</p>
                <input type="password" name="password" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.3);">
                @error('password')
                    <p style="color: #dc3545; font-size: 0.85rem; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" 
                       style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.3);">
            </div>
            
            <button type="submit" class="btn" style="width: 100%;">💾 Mettre à jour</button>
        </form>
    </div>
    
    <!-- Niveau d'étude -->
    <div class="card">
        <h3>🎓 Mon niveau d'étude</h3>
        
        <div style="background: rgba(88, 204, 2, 0.1); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <p><strong>Niveau actuel :</strong> 
                <span style="font-size: 1.2rem; font-weight: bold; color: #58cc02;">
                    {{ $user->niveau ? $user->niveau->code : 'Non défini' }}
                </span>
            </p>
            <p style="font-size: 0.9rem; opacity: 0.7;">
                {{ $user->niveau ? $user->niveau->libelle : 'Veuillez sélectionner votre niveau' }}
            </p>
        </div>
        
        <form method="POST" action="{{ route('user.profile.choose-level') }}">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 5px;">Choisir mon niveau</label>
                <select name="niveau_id" required 
                        style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255,255,255,0.3);">
                    <option value="">-- Sélectionner --</option>
                    @foreach($niveaux as $niveau)
                        <option value="{{ $niveau->id }}" {{ $user->niveau_id == $niveau->id ? 'selected' : '' }}>
                            {{ $niveau->code }} - {{ $niveau->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn" style="width: 100%;">📌 Enregistrer</button>
        </form>
        
        <div style="margin-top: 20px; padding: 15px; background: rgba(0, 150, 255, 0.1); border-radius: 10px;">
            <h4 style="font-size: 0.9rem;">📊 Statistiques</h4>
            <p style="font-size: 0.85rem;">
                Cours terminés : <strong>{{ \App\Models\Progression::where('user_id', $user->id)->where('statut', 'termine')->count() }}</strong>
            </p>
            <p style="font-size: 0.85rem;">
                Exercices soumis : <strong>{{ \App\Models\Soumission::where('user_id', $user->id)->count() }}</strong>
            </p>
            <p style="font-size: 0.85rem;">
                Membre depuis : <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
            </p>
        </div>
    </div>
</div>
@endsection