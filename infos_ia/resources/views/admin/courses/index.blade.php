@extends('layouts.admin')

@section('title', 'Gestion des cours')

@section('content')
<div class="glass block">
    <h3 style="color: #1f2937; font-weight: 700;">📚 Gestion des cours</h3>

    <div style="margin-top: 15px; margin-bottom: 15px;">
        <a href="{{ route('admin.courses.create') }}" style="display: inline-block; padding: 10px 24px; background: #58cc02; color: #fff; border-radius: 30px; text-decoration: none; font-weight: 600;">➕ Ajouter un cours</a>
    </div>

    @if(session('success'))
        <div style="padding: 12px; background: rgba(88, 204, 2, 0.2); border-radius: 10px; margin-bottom: 15px; color: #146c43; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding: 12px; background: rgba(220, 53, 69, 0.15); border-radius: 10px; margin-bottom: 15px; color: #dc3545; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.courses.bulk-action') }}" id="bulk-form">
        @csrf
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <thead>
                <tr style="background: rgba(0,0,0,0.06);">
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);"><input type="checkbox" id="selectAll"></th>
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);">Titre</th>
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);">Module</th>
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);">Niveau</th>
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);">Statut</th>
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);">Fichiers</th>
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);">Créé le</th>
                    <th style="padding: 12px; text-align: left; color: #1f2937; font-weight: 700; border-bottom: 2px solid rgba(0,0,0,0.1);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr @if(!$course->module) style="background: rgba(220,53,69,0.06);" @endif>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #1f2937;"><input type="checkbox" name="courses[]" value="{{ $course->id }}" class="course-checkbox"></td>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #1f2937;">{{ Str::limit($course->titre, 30) }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #1f2937;">
                        @if($course->module)
                            {{ $course->module->titre }}
                        @else
                            <span style="color: #dc3545; font-weight: 600;" title="Le module de ce cours a été supprimé">⚠️ Module supprimé</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #1f2937;">{{ $course->module?->niveau?->code ?? '—' }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06);">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; background: 
                            @if($course->statut == 'publie') #c8f7dc; color: #146c43;
                            @elseif($course->statut == 'brouillon') #fff3cd; color: #856404;
                            @else #e9ecef; color: #6c757d; @endif">
                            {{ $course->statut }}
                        </span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #1f2937;">
                        @php
                            $fichiers = $course->fichier ? explode(',', $course->fichier) : [];
                            $fichiers = array_filter($fichiers);
                        @endphp
                        @if(count($fichiers) > 0)
                            <span style="font-size: 0.85rem;">📎 {{ count($fichiers) }}</span>
                        @else
                            <span style="color: #999;">—</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #1f2937;">{{ $course->created_at->format('d/m/Y') }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid rgba(0,0,0,0.06);">
                        <a href="{{ route('admin.courses.show', $course->id) }}" style="font-size: 0.8rem; padding: 4px 12px; border: 2px solid #1f2937; color: #1f2937; border-radius: 30px; text-decoration: none; display: inline-block;">👁 Voir</a>
                        <a href="{{ route('admin.courses.edit', $course->id) }}" style="font-size: 0.8rem; padding: 4px 12px; border: 2px solid #1f2937; color: #1f2937; border-radius: 30px; text-decoration: none; display: inline-block;">✏ Modifier</a>
                        <button type="button" onclick="deleteCourse({{ $course->id }})" style="font-size: 0.8rem; padding: 4px 12px; background: #dc3545; color: #fff; border: none; border-radius: 30px; cursor: pointer;">🗑 Supprimer</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: #6c757d;">
                        Aucun cours pour le moment. <a href="{{ route('admin.courses.create') }}" style="color: #58cc02; font-weight: 600;">Ajouter un cours</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="submit" name="action" value="delete" class="btn-danger" onclick="return confirm('Supprimer les cours sélectionnés ?')" style="padding: 8px 20px; background: #dc3545; color: #fff; border: none; border-radius: 30px; cursor: pointer; font-weight: 600;">🗑 Supprimer sélectionnés</button>
        </div>
    </form>

    <!-- Formulaire caché unique, utilisé pour la suppression individuelle (évite d'imbriquer un <form> dans le formulaire bulk-action ci-dessus) -->
    <form method="POST" id="delete-course-form" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <div style="margin-top: 20px;">
        {{ $courses->links() }}
    </div>
</div>

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.course-checkbox').forEach(cb => cb.checked = this.checked);
    });

    function deleteCourse(id) {
        if (!confirm('Supprimer ce cours ?')) return;
        const form = document.getElementById('delete-course-form');
        form.action = `{{ url('admin/courses') }}/${id}`;
        form.submit();
    }
</script>
@endsection