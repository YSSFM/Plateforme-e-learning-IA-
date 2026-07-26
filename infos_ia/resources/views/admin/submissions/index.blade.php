@extends('layouts.admin')

@section('title', 'Soumissions des étudiants')

@section('content')
<div class="glass block">
    <h3>Soumissions des étudiants</h3>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Exercice</th>
                <th>Fichier</th>
                <th>Note</th>
                <th>Statut</th>
                <th>Soumis le</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $sub)
            <tr>
                <td>{{ $sub->user->username ?? '—' }}</td>
                <td>{{ $sub->exercice->titre ?? '⚠️ Exercice supprimé' }}</td>
                <td>
                    @if($sub->fichier)
                        <a href="{{ asset('storage/submissions/' . $sub->fichier) }}" target="_blank" class="btn-outline">📄 Télécharger</a>
                    @else
                        —
                    @endif
                </td>
                <td>{{ $sub->note ?? '—' }}</td>
                <td>
                    @if($sub->statut == 'corrige')
                        <span class="badge publie">corrigé</span>
                    @else
                        <span class="badge brouillon">{{ $sub->statut }}</span>
                    @endif
                </td>
                <td>{{ $sub->created_at->format('d/m/Y H:i') }}</td>
                <td style="white-space: nowrap;">
                    <a href="{{ route('admin.submissions.grade', $sub->id) }}" class="btn">✏ Corriger</a>
                    <button type="button" class="btn-danger" onclick="deleteSubmission({{ $sub->id }})">🗑 Supprimer</button>
                </td>
            </tr>
            @empty
            <tr><td colspan="7">Aucune soumission</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Formulaire caché unique pour la suppression (évite d'imbriquer un <form> dans une <table>) -->
    <form method="POST" id="delete-submission-form" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <div style="margin-top: 20px;">
        {{ $submissions->links() }}
    </div>
</div>

<script>
    function deleteSubmission(id) {
        if (!confirm('Supprimer cette soumission ? L\'étudiant pourra la resoumettre si la date limite n\'est pas dépassée.')) return;
        const form = document.getElementById('delete-submission-form');
        form.action = `{{ url('admin/submissions') }}/${id}`;
        form.submit();
    }
</script>
@endsection