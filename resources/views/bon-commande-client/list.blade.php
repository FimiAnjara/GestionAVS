@extends('layouts.app')

@section('title', 'Liste des Bons de Commande Client')

@section('header-buttons')
    <a href="{{ route('bon-commande-client.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouveau Bon client
    </a>
@endsection

@section('content')
    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtres et Recherche
            </h6>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="{{ route('bon-commande-client.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-2">
                    <label for="id" class="form-label">ID</label>
                    <input type="text" class="form-control form-control-sm" id="id" name="id"
                        placeholder="BCC_..." value="{{ request('id') }}">
                </div>

                <div class="col-lg-2">
                    <label for="client" class="form-label">Client</label>
                    <select class="form-select form-select-sm" id="client" name="client">
                        <option value="">-- Tous --</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id_client }}"
                                {{ request('client') == $client->id_client ? 'selected' : '' }}>
                                {{ $client->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label for="magasin" class="form-label">Magasin</label>
                    <select class="form-select form-select-sm" id="magasin" name="magasin">
                        <option value="">-- Tous --</option>
                        @foreach ($magasins as $magasin)
                            <option value="{{ $magasin->id_magasin }}"
                                {{ request('magasin') == $magasin->id_magasin ? 'selected' : '' }}>
                                {{ $magasin->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label for="date_from" class="form-label">De</label>
                    <input type="date" class="form-control form-control-sm" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>

                <div class="col-lg-2">
                    <label for="etat" class="form-label">État</label>
                    <select class="form-select form-select-sm" id="etat" name="etat">
                        <option value="">-- Tous --</option>
                        <option value="1" {{ request('etat') == '1' ? 'selected' : '' }}>Créée</option>
                        <option value="11" {{ request('etat') == '11' ? 'selected' : '' }}>Validée</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('bon-commande-client.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Bons de Commande -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Bons de Commande Client ({{ $bonCommandes->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($bonCommandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Magasin</th>
                                <th>Proforma</th>
                                <th>État</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bonCommandes as $bc)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $bc->id_bon_commande_client }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $bc->date_->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $bc->client->nom }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            @if($bc->magasin)
                                                <i class="bi bi-shop me-1 text-primary"></i>{{ $bc->magasin->nom }}
                                            @else
                                                <span class="text-danger italic">Non spécifié</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <small>{{ $bc->id_proforma_client ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $bc->etat == 1 ? 'warning' : 'success' }}">
                                            {{ $bc->etat == 1 ? 'Créée' : 'Validée' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('bon-commande-client.show', $bc->id_bon_commande_client) }}"
                                                class="btn btn-info" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('bon-commande-client.exportPdf', $bc->id_bon_commande_client) }}"
                                                class="btn btn-success" title="Exporter PDF" target="_blank">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-bs-url="{{ route('bon-commande-client.destroy', $bc->id_bon_commande_client) }}"
                                                data-bs-item="le bon de commande client {{ $bc->id_bon_commande_client }}"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Affichage de {{ $bonCommandes->firstItem() }} à {{ $bonCommandes->lastItem() }} sur {{ $bonCommandes->total() }} bons
                    </small>
                    <nav aria-label="pagination">
                        {{ $bonCommandes->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun bon de commande trouvé</p>
                    <a href="{{ route('bon-commande-client.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>Créer un bon client
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
