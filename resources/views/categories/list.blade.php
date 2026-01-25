@extends('layouts.app')

@section('title', 'Liste des Catégories')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted">Consultez et gérez vos catégories d'articles</p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Ajouter Catégorie
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-primary text-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4"><i class="bi bi-hash"></i> ID</th>
                        <th><i class="bi bi-tag"></i> Libellé</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th class="text-center"><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    @forelse($categories as $categorie)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-info text-dark" style="word-break: break-all;">{{ $categorie->id_categorie }}</span>
                            </td>
                            <td>
                                <strong>{{ $categorie->libelle }}</strong>
                                @if($categorie->est_perissable)
                                    <span class="badge bg-danger ms-2" title="Périssable"><i class="bi bi-clock-history"></i> Périssable</span>
                                @else
                                    <span class="badge bg-light text-dark border ms-2" title="Non périssable">Non périssable</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $categorie->created_at->format('d/m/Y') }}</small></td>
                            <td class="text-center">
                                <a href="{{ route('categories.show', $categorie->id_categorie) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('categories.edit', $categorie->id_categorie) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteItem('{{ $categorie->id_categorie }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Aucune catégorie</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $categories->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#categoriesTableBody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });

    function deleteItem(id) {
        if (confirm('Supprimer cette catégorie ?')) {
            fetch('{{ route("categories.destroy", ":id") }}'.replace(':id', id), {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'}
            }).then(r => r.json()).then(d => {
                if (d.success) { alert(d.message); location.reload(); }
            });
        }
    }
</script>
@endsection
