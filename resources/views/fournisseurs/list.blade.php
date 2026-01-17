@extends('layouts.app')

@section('title', 'Liste des Fournisseurs')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-building"></i> Gestion des Fournisseurs</h2>
            <p class="text-muted">Consultez et gérez tous vos fournisseurs</p>
        </div>
        <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Ajouter Fournisseur
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-primary text-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom...">
            </div>
        </div>
    </div>

    <!-- Tableau des fournisseurs -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4"><i class="bi bi-hash"></i> ID</th>
                        <th><i class="bi bi-building"></i> Nom</th>
                        <th><i class="bi bi-geo-alt"></i> Lieu</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th class="text-center"><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody id="fournisseursTableBody">
                    @forelse($fournisseurs as $fournisseur)
                        <tr data-fournisseur-id="{{ $fournisseur->id_fournisseur }}">
                            <td class="ps-4">
                                <span class="badge bg-info text-dark">{{ substr($fournisseur->id_fournisseur, 0, 8) }}</span>
                            </td>
                            <td><strong class="text-dark">{{ $fournisseur->nom }}</strong></td>
                            <td>
                                <i class="bi bi-geo-alt"></i> {{ $fournisseur->lieux }}
                            </td>
                            <td>
                                <small class="text-muted">{{ $fournisseur->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('fournisseurs.show', $fournisseur->id_fournisseur) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('fournisseurs.edit', $fournisseur->id_fournisseur) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteFournisseur('{{ $fournisseur->id_fournisseur }}')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Aucun fournisseur trouvé</p>
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
        const rows = document.querySelectorAll('#fournisseursTableBody tr');
        
        rows.forEach(row => {
            const nom = row.querySelector('td:nth-child(2)');
            if (nom) {
                const isVisible = nom.textContent.toLowerCase().includes(searchValue);
                row.style.display = isVisible ? '' : 'none';
            }
        });
    });

    function deleteFournisseur(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')) {
            fetch('{{ route("fournisseurs.destroy", ":id") }}'.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            });
        }
    }
</script>
@endsection
