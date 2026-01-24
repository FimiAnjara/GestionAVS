@extends('layouts.app')

@section('title', 'Liste des Factures Fournisseur')

@section('header-buttons')
    <a href="{{ route('facture-fournisseur.create') }}" class="btn btn-primary">
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
            <form method="GET" action="{{ route('facture-fournisseur.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-3">
                    <label for="id" class="form-label">ID Facture</label>
                    <input type="text" class="form-control form-control-sm" id="id" name="id" 
                           placeholder="FACT_..." value="{{ request('id') }}">
                </div>

                <div class="col-lg-2">
                    <label for="date_from" class="form-label">De</label>
                    <input type="date" class="form-control form-control-sm" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>

                <div class="col-lg-2">
                    <label for="date_to" class="form-label">À</label>
                    <input type="date" class="form-control form-control-sm" id="date_to" name="date_to"
                        value="{{ request('date_to') }}">
                </div>

                <div class="col-lg-2">
                    <label for="etat" class="form-label">État</label>
                    <select class="form-select form-select-sm" id="etat" name="etat">
                        <option value="">-- Tous les états --</option>
                        <option value="1" {{ request('etat') == '1' ? 'selected' : '' }}>Créée</option>
                        <option value="5" {{ request('etat') == '5' ? 'selected' : '' }}>Validée par Finance</option>
                        <option value="11" {{ request('etat') == '11' ? 'selected' : '' }}>Validée par DG</option>
                        <option value="0" {{ request('etat') == '0' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

    <!-- Liste des Factures -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Factures ({{ $factures->total() }} total)
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
                                <th>Bon Commande</th>
                                <th>Montant Total</th>
                                <th>Montant Payé</th>
                                <th>Reste à Payer</th>
                                <th>État</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($factures as $facture)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $facture->id_factureFournisseur }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $facture->date_->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        @if($facture->bonCommande)
                                            <span class="badge bg-info text-dark">{{ $facture->bonCommande->id_bonCommande }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ number_format($facture->montant_total ?? 0, 0, ',', ' ') }} Ar</small>
                                    </td>
                                    <td>
                                        <small>{{ number_format($facture->montant_paye ?? 0, 0, ',', ' ') }} Ar</small>
                                    </td>
                                    <td>
                                        <small class="{{ ($facture->reste_a_payer ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                            <strong>{{ number_format($facture->reste_a_payer ?? 0, 0, ',', ' ') }} Ar</strong>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $facture->etat_badge }}">{{ $facture->etat_label }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('facture-fournisseur.show', $facture->id_factureFournisseur) }}"
                                                class="btn btn-info" title="Voir">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('facture-fournisseur.exportPdf', $facture->id_factureFournisseur) }}"
                                                class="btn btn-success" title="Exporter PDF" target="_blank">
                                                <i class="bi bi-file-pdf"></i>
                                            </a>
                                            @if($facture->etat != 0 && $facture->etat != 11)
                                                <a href="#" class="btn btn-warning"
                                                    data-bs-toggle="modal" data-bs-target="#changeEtatModal{{ $facture->id_factureFournisseur }}"
                                                    title="Changer état">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-bs-url="{{ route('facture-fournisseur.destroy', $facture->id_factureFournisseur) }}"
                                                data-bs-item="la facture {{ $facture->id_factureFournisseur }}"
                                                title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Changer État -->
                                <div class="modal fade" id="changeEtatModal{{ $facture->id_factureFournisseur }}"
                                    tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Changer l'État</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form
                                                action="{{ route('facture-fournisseur.changerEtat', $facture->id_factureFournisseur) }}"
                                                method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <label for="etat" class="form-label">Nouvel État</label>
                                                    <select class="form-select" name="etat" required>
                                                        @if ($facture->etat == 1)
                                                            <option value="5">Validée par Finance</option>
                                                            <option value="0">Annulée</option>
                                                        @elseif($facture->etat == 5)
                                                            <option value="11">Validée par DG</option>
                                                            <option value="1">Retour à Créée</option>
                                                            <option value="0">Annulée</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Valider</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
        <div class="d-flex justify-content-center mt-4">
            {{ $factures->links() }}
        </div>
    @endif

@endsection
