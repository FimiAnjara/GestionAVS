@extends('layouts.app')

@section('title', 'Commandes Clients')

@section('header-buttons')
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 py-3">
        <h5 class="mb-0">
            <i class="bi bi-list-check me-2" style="color: #0056b3;"></i>
            Liste des Commandes Clients
        </h5>
    </div>
    <div class="card-body p-4">
        <!-- Filtres -->
        <div class="card bg-light mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('commande.list') }}" class="row g-3">
                    <div class="col-lg-2">
                        <label for="id" class="form-label small">ID Commande</label>
                        <input type="text" class="form-control form-control-sm" id="id" name="id" 
                            value="{{ request('id') }}" placeholder="CMD_0001">
                    </div>
                    <div class="col-lg-3">
                        <label for="id_client" class="form-label small">Client</label>
                        <select class="form-select form-select-sm" id="id_client" name="id_client">
                            <option value="">-- Tous les clients --</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id_client }}" {{ request('id_client') == $client->id_client ? 'selected' : '' }}>
                                    {{ $client->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label for="date_from" class="form-label small">Du</label>
                        <input type="date" class="form-control form-control-sm" id="date_from" name="date_from" 
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-lg-2">
                        <label for="date_to" class="form-label small">Au</label>
                        <input type="date" class="form-control form-control-sm" id="date_to" name="date_to" 
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-lg-2">
                        <label for="etat" class="form-label small">État</label>
                        <select class="form-select form-select-sm" id="etat" name="etat">
                            <option value="">-- Tous --</option>
                            <option value="1" {{ request('etat') == '1' ? 'selected' : '' }}>Créée</option>
                            <option value="2" {{ request('etat') == '2' ? 'selected' : '' }}>Confirmée</option>
                            <option value="3" {{ request('etat') == '3' ? 'selected' : '' }}>Expédiée</option>
                        </select>
                    </div>
                    <div class="col-lg-1 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        <a href="{{ route('commande.list') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID Commande</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>État</th>
                        <th>Utilisateur</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commandes as $commande)
                        <tr>
                            <td>
                                <a href="{{ route('commande.show', $commande->id_commande) }}" class="text-decoration-none">
                                    <strong>{{ $commande->id_commande }}</strong>
                                </a>
                            </td>
                            <td>{{ $commande->client?->nom ?? 'N/A' }}</td>
                            <td>{{ $commande->date_ ? $commande->date_->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($commande->etat == 1)
                                    <span class="badge bg-warning text-dark">Créée</span>
                                @elseif($commande->etat == 2)
                                    <span class="badge bg-info">Confirmée</span>
                                @elseif($commande->etat == 3)
                                    <span class="badge bg-success">Expédiée</span>
                                @else
                                    <span class="badge bg-secondary">Inconnue</span>
                                @endif
                            </td>
                            <td>{{ $commande->utilisateur?->nom_utilisateur ?? 'N/A' }}</td>
                            <td class="text-center">
                                <a href="{{ route('commande.show', $commande->id_commande) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" 
                                    onclick="deleteCommande('{{ $commande->id_commande }}')" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Aucune commande trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($commandes->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $commandes->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function deleteCommande(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("commande.destroy", ":id") }}'.replace(':id', id);
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
