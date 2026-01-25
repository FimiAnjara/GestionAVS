@extends('layouts.app')

@section('title', 'Types d\'Évaluation de Stock')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted">Gérez les méthodes de valorisation de votre inventaire</p>
        </div>
        <a href="{{ route('type-evaluation-stock.create') }}" class="btn btn-primary d-flex align-items-center shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Ajouter un Type
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Rechercher un type (code ou libellé)...">
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small text-uppercase">Code</th>
                        <th class="py-3 text-muted small text-uppercase" style="width: 250px;">Libellé</th>
                        <th class="py-3 text-muted small text-uppercase">Description</th>
                        <th class="py-3 text-muted small text-uppercase text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="typesTableBody">
                    @forelse($types as $type)
                        <tr>
                            <td class="px-4">
                                <span class="badge bg-info text-dark">{{ $type->id_type_evaluation_stock }}</span>
                            </td>
                            <td class="fw-bold">{{ $type->libelle }}</td>
                            <td>
                                <p class="text-muted small mb-0">{{ \Illuminate\Support\Str::limit($type->description, 150) }}</p>
                            </td>
                            <td class="text-center px-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('type-evaluation-stock.show', $type->id_type_evaluation_stock) }}" class="btn btn-sm btn-info text-white" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('type-evaluation-stock.edit', $type->id_type_evaluation_stock) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger text-white" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteConfirmModal"
                                            data-bs-url="{{ route('type-evaluation-stock.destroy', $type->id_type_evaluation_stock) }}"
                                            data-bs-item="{{ $type->libelle }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Aucun type d'évaluation trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Recherche en temps réel
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#typesTableBody tr');
        
        rows.forEach(row => {
            const code = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            const libelle = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            const isVisible = code.includes(searchValue) || libelle.includes(searchValue);
            row.style.display = isVisible ? '' : 'none';
        });
    });
</script>
@endsection
