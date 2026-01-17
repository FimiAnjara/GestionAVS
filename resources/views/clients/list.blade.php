@extends('layouts.app')

@section('title', 'Liste des Clients')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-people"></i> Gestion des Clients</h2>
            <p class="text-muted">Consultez et gérez tous vos clients</p>
        </div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Ajouter Client
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

    <!-- Tableau des clients -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4"><i class="bi bi-hash"></i> ID</th>
                        <th><i class="bi bi-person"></i> Nom</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th class="text-center"><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody">
                    @forelse($clients as $client)
                        <tr data-client-id="{{ $client->id_client }}">
                            <td class="ps-4">
                                <span class="badge bg-info text-dark">{{ substr($client->id_client, 0, 8) }}</span>
                            </td>
                            <td><strong class="text-dark">{{ $client->nom }}</strong></td>
                            <td>
                                <small class="text-muted">{{ $client->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('clients.show', $client->id_client) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clients.edit', $client->id_client) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteClient('{{ $client->id_client }}')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Aucun client trouvé</p>
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
        const rows = document.querySelectorAll('#clientsTableBody tr');
        
        rows.forEach(row => {
            const nom = row.querySelector('td:nth-child(2)');
            if (nom) {
                const isVisible = nom.textContent.toLowerCase().includes(searchValue);
                row.style.display = isVisible ? '' : 'none';
            }
        });
    });

    function deleteClient(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
            fetch('{{ route("clients.destroy", ":id") }}'.replace(':id', id), {
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
