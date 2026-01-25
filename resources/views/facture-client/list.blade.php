@extends('layouts.app')

@section('title', 'Liste des Factures Client')

@section('header-buttons')
    <a href="{{ route('facture-client.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouvelle Facture
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
            <form method="GET" action="{{ route('facture-client.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-3">
                    <label for="id" class="form-label">ID Facture</label>
                    <input type="text" class="form-control form-control-sm" id="id" name="id" 
                           placeholder="FACTC_..." value="{{ request('id') }}">
                </div>

                <div class="col-lg-3">
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
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('facture-client.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Factures -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Factures Client ({{ $factures->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($factures->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Bon Commande</th>
                                <th>Montant Total</th>
                                <th>État</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($factures as $facture)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $facture->id_facture_client }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $facture->date_->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $facture->client->nom }}</span>
                                    </td>
                                    <td>
                                        @if($facture->bonCommandeClient)
                                            <span class="badge bg-info text-dark">{{ $facture->id_bon_commande_client }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $total = $facture->factureClientFille->sum(function($f) { return $f->quantite * $f->prix; });
                                        @endphp
                                        <small>{{ number_format($total, 0, ',', ' ') }} Ar</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $facture->etat == 1 ? 'warning' : 'success' }}">
                                            {{ $facture->etat == 1 ? 'Créée' : 'Validée' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('facture-client.show', $facture->id_facture_client) }}"
                                                class="btn btn-info" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('facture-client.exportPdf', $facture->id_facture_client) }}"
                                                class="btn btn-success" title="Exporter PDF" target="_blank">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-bs-url="{{ route('facture-client.destroy', $facture->id_facture_client) }}"
                                                data-bs-item="la facture {{ $facture->id_facture_client }}"
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
            @else
                <div class="alert alert-info mb-0 p-4 text-center">
                    <i class="bi bi-inbox me-2"></i>Aucune facture trouvée
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if ($factures->count() > 0)
        <div class="p-3 border-top">
            {{ $factures->links() }}
        </div>
    @endif

@endsection
