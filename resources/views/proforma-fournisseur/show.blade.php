@extends('layouts.app')

@section('title', 'Détails de la Proforma')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('proforma-fournisseur.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Informations Générales -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark me-2"></i>Informations Générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>ID Proforma:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-primary">{{ $proforma->id_proformaFournisseur }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Date:</strong></div>
                        <div class="col-sm-7"><small>{{ $proforma->date_->format('d/m/Y') }}</small></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Fournisseur:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-info text-dark">{{ $proforma->fournisseur->nom }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>État:</strong></div>
                        <div class="col-sm-7">
                            <span class="badge bg-{{ $proforma->etat_badge }}">{{ $proforma->etat_label }}</span>
                        </div>
                    </div>
                    @if($proforma->magasin)
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Magasin Destination:</strong></div>
                        <div class="col-sm-7">
                            <span class="fw-bold">{{ $proforma->magasin->nom }}</span><br>
                            <small class="text-muted">{{ $proforma->magasin->site?->localisation }}</small>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-5"><strong>Créée le:</strong></div>
                        <div class="col-sm-7"><small class="text-muted">{{ $proforma->created_at->format('d/m/Y H:i') }}</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calculator me-2"></i>Résumé Financier
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalQuantite = $proforma->proformaFournisseurFille->sum(function($item) { 
                            return $item->quantite ?? 1; 
                        });
                        $totalMontant = $proforma->proformaFournisseurFille->sum(function($item) { 
                            return ($item->quantite ?? 1) * ($item->prix_achat ?? 0); 
                        });
                    @endphp
                    <div class="row mb-3">
                        <div class="col-sm-6"><strong>Nombre d'articles:</strong></div>
                        <div class="col-sm-6"><span class="badge bg-info fs-6">{{ $proforma->proformaFournisseurFille->count() }}</span></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"><strong>Montant Total:</strong></div>
                        <div class="col-sm-6"><span class="badge bg-success fs-6">{{ number_format($totalMontant, 0, ',', ' ') }} Ar</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles de la Proforma -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-basket me-2"></i>Articles
            </h5>
        </div>
        <div class="card-body p-0">
            @if($proforma->proformaFournisseurFille->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Article</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Prix Unitaire</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proforma->proformaFournisseurFille as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->article->nom }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $item->article->id_article }}</small>
                                    </td>
                                    <td class="text-end">{{ number_format($item->quantite ?? 1, 2, ',', ' ') }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-warning">{{ number_format($item->prix_achat ?? 0, 0, ',', ' ') }} Ar</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format(($item->quantite ?? 1) * ($item->prix_achat ?? 0), 0, ',', ' ') }} Ar</strong>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-light fw-bold">
                                <td colspan="2" class="text-end">TOTAL:</td>
                                <td></td>
                                <td class="text-end">
                                    <span class="badge bg-success fs-6">
                                        {{ number_format($totalMontant, 0, ',', ' ') }} Ar
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun article dans cette proforma</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Description -->
    @if($proforma->description)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="mb-0">
                    <i class="bi bi-file-text me-2"></i>Description
                </h6>
            </div>
            <div class="card-body">
                {{ $proforma->description }}
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('proforma-fournisseur.exportPdf', $proforma->id_proformaFournisseur) }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-file-pdf me-2"></i>Exporter PDF
                </a>
                @if($proforma->etat == 1)
                    <a href="{{ route('proforma-fournisseur.edit', $proforma->id_proformaFournisseur) }}" class="btn btn-secondary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#changeEtatModal">
                        <i class="bi bi-pencil me-2"></i>Valider par Finance
                    </button>
                @elseif($proforma->etat == 5)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changeEtatModal">
                        <i class="bi bi-pencil me-2"></i>Valider par DG
                    </button>
                    <a href="{{ route('bon-commande.create', ['proforma_id' => $proforma->id_proformaFournisseur]) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-plus me-2"></i>Créer Bon de Commande
                    </a>
                @endif
                <button type="button" class="btn btn-danger" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteConfirmModal"
                    data-bs-url="{{ route('proforma-fournisseur.destroy', $proforma->id_proformaFournisseur) }}"
                    data-bs-item="la proforma {{ $proforma->id_proformaFournisseur }}"
                    title="Annuler">
                    <i class="bi bi-trash me-2"></i>Annuler
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Changer État -->
    <div class="modal fade" id="changeEtatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changer l'État de la Proforma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('proforma-fournisseur.etat', $proforma->id_proformaFournisseur) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label for="etat" class="form-label">Nouvel État</label>
                        <select class="form-select" name="etat" required>
                            @if($proforma->etat == 1)
                                <option value="5">Validée par Finance</option>
                                <option value="0">Annulée</option>
                            @elseif($proforma->etat == 5)
                                <option value="11">Validée par DG</option>
                                <option value="1">Retour à Créée</option>
                                <option value="0">Annulée</option>
                            @elseif($proforma->etat == 11)
                                <option value="1">Retour à Créée</option>
                                <option value="0">Annulée</option>
                            @endif
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
