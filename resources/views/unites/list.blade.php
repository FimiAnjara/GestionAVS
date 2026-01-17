@extends('layouts.app')

@section('title', 'Liste des Unités')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-rulers"></i> Gestion des Unités</h2>
            <p class="text-muted">Consultez et gérez vos unités de mesure</p>
        </div>
        <a href="{{ route('unites.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Ajouter Unité
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4"><i class="bi bi-hash"></i> ID</th>
                        <th><i class="bi bi-rulers"></i> Libellé</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th class="text-center"><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody id="unitesTableBody">
                    @forelse($unites as $unite)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-info text-dark" style="word-break: break-all;">{{ $unite->id_unite }}</span>
                            </td>
                            <td><strong>{{ $unite->libelle }}</strong></td>
                            <td><small class="text-muted">{{ $unite->created_at->format('d/m/Y') }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('unites.show', $unite->id_unite) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('unites.edit', $unite->id_unite) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteItem('{{ $unite->id_unite }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Aucune unité</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $unites->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#unitesTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    function deleteItem(id) {
        if (confirm('Supprimer cette unité ?')) {
            fetch('{{ route("unites.destroy", ":id") }}'.replace(':id', id), {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
            }).then(r => r.json()).then(d => {
                if (d.success) { alert(d.message); location.reload(); }
            });
        }
    }
</script>
@endsection
