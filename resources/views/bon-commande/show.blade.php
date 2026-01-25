@extends('layouts.app')

@section('title', 'Détails Bon de Commande')

@section('header-buttons')
    <a href="{{ route('bon-commande.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-lg-8">
            <!-- Informations générales -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark me-2"></i>Informations Générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label text-muted">ID</label>
                            <p class="fw-bold text-primary">{{ $bonCommande->id_bonCommande }}</p>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-muted">Date</label>
                            <p class="fw-bold">{{ $bonCommande->date_->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label text-muted">Fournisseur</label>
                            <p class="fw-bold">{{ $bonCommande->proformaFournisseur->fournisseur->nom }}</p>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label text-muted">Proforma</label>
                            <p class="fw-bold">
                                <a href="{{ route('proforma-fournisseur.show', $bonCommande->proformaFournisseur->id_proformaFournisseur) }}" class="text-decoration-none">
                                    {{ $bonCommande->proformaFournisseur->id_proformaFournisseur }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">État</label>
                        <p class="fw-bold">
                            <span class="badge bg-{{ $bonCommande->etat == 1 ? 'warning' : ($bonCommande->etat == 5 ? 'info' : 'success') }} fs-6">
                                {{ $bonCommande->etat == 1 ? 'Créée' : ($bonCommande->etat == 5 ? 'Validée par Finance' : 'Validée par DG') }}
                            </span>
                        </p>
                    </div>
                    @if($bonCommande->magasin)
                    <div class="mb-3">
                        <label class="form-label text-muted">Magasin Destination</label>
                        <p class="fw-bold">
                            {{ $bonCommande->magasin->nom }}<br>
                            <small class="text-muted fw-normal">{{ $bonCommande->magasin->site?->localisation }}</small>
                        </p>
                    </div>
                    @endif
                    @if ($bonCommande->description)
                        <div class="mb-0">
                            <label class="form-label text-muted">Description</label>
                            <p>{{ $bonCommande->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Articles -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-box me-2"></i>Articles ({{ $articles->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">Photo</th>
                                        <th>Article</th>
                                        <th class="text-end">Quantité</th>
                                        <th class="text-end">Prix Unitaire (Ar)</th>
                                        <th class="text-end">Montant (Ar)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach ($articles as $article)
                                        @php $montant = $article->quantite * $article->prix_achat; $total += $montant; @endphp
                                        <tr>
                                            <td class="text-center">
                                                @if($article->article->photo)
                                                    <img src="{{ asset('storage/' . $article->article->photo) }}" 
                                                         class="rounded shadow-sm" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $article->article->nom }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $article->id_article }}</small>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($article->quantite, 2, ',', ' ') }}
                                                <span class="badge bg-success ms-1">{{ $article->article->unite->libelle ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($article->prix_achat, 2, ',', ' ') }}</td>
                                            <td class="text-end fw-bold">{{ number_format($montant, 2, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL</th>
                                        <th class="text-end fw-bold">{{ number_format($total, 2, ',', ' ') }} Ar</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Aucun article</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Exporter PDF -->
                        <a href="{{ route('bon-commande.exportPdf', $bonCommande->id_bonCommande) }}" 
                            class="btn btn-success" target="_blank">
                            <i class="bi bi-file-pdf me-2"></i>Exporter PDF
                        </a>

                        <!-- Créer Facture (si état = 11 et pas de facture déjà créée) -->
                        @if ($bonCommande->etat == 11 && !$bonCommande->id_factureFournisseur)
                            <a href="{{ route('facture-fournisseur.createFromBonCommande', $bonCommande->id_bonCommande) }}" 
                                class="btn btn-primary">
                                <i class="bi bi-receipt me-2"></i>Créer Facture
                            </a>
                        @elseif($bonCommande->id_factureFournisseur)
                            <a href="{{ route('facture-fournisseur.show', $bonCommande->id_factureFournisseur) }}" 
                                class="btn btn-info">
                                <i class="bi bi-receipt me-2"></i>Voir Facture
                            </a>
                        @endif

                        <!-- Valider par DG (si état = 5) -->
                        @if ($bonCommande->etat == 5)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                                data-bs-target="#validateModal">
                                <i class="bi bi-check-circle me-2"></i>Valider par DG
                            </button>
                        @endif

                        <!-- Retour à Créée (si état > 1) -->
                        @if ($bonCommande->etat > 1)
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" 
                                data-bs-target="#revertModal">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Retour à Créée
                            </button>
                        @endif

                        <!-- Valider par Finance (si état = 1) -->
                        @if ($bonCommande->etat == 1)
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" 
                                data-bs-target="#validateFinanceModal">
                                <i class="bi bi-check-circle me-2"></i>Valider par Finance
                            </button>
                        @endif

                        <!-- Supprimer -->
                        <button type="button" class="btn btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteConfirmModal"
                            data-bs-url="{{ route('bon-commande.destroy', $bonCommande->id_bonCommande) }}"
                            data-bs-item="le bon de commande {{ $bonCommande->id_bonCommande }}"
                            title="Supprimer">
                            <i class="bi bi-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Valider par Finance -->
    <div class="modal fade" id="validateFinanceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Valider par Finance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('bon-commande.etat', $bonCommande->id_bonCommande) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir valider ce bon par Finance ?</p>
                        <input type="hidden" name="etat" value="5">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Valider par DG -->
    <div class="modal fade" id="validateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Valider par DG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('bon-commande.etat', $bonCommande->id_bonCommande) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir valider ce bon par le DG ?</p>
                        <input type="hidden" name="etat" value="11">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Retour à Créée -->
    <div class="modal fade" id="revertModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Retour à Créée</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('bon-commande.etat', $bonCommande->id_bonCommande) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir revenir à l'état Créée ?</p>
                        <input type="hidden" name="etat" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Revenir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
