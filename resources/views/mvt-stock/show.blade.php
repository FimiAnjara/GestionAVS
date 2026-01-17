@extends('layouts.app')

@section('title', 'Détails du Mouvement - ' . $mouvement->id_mvt_stock)

@section('header-buttons')
    <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
    <a href="{{ route('mvt-stock.exportPdf', $mouvement->id_mvt_stock) }}" class="btn btn-success" target="_blank">
        <i class="bi bi-file-pdf me-2"></i>Exporter PDF
    </a>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informations du Mouvement
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">ID</label>
                            <p class="form-control-plaintext fw-bold text-primary">{{ $mouvement->id_mvt_stock }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Date</label>
                            <p class="form-control-plaintext">{{ $mouvement->date_->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Type</label>
                            <p class="form-control-plaintext">
                                @if ($mouvement->entree > 0)
                                    <span class="badge bg-success">ENTRÉE</span>
                                @else
                                    <span class="badge bg-danger">SORTIE</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Quantité</label>
                            <p class="form-control-plaintext fw-bold">
                                {{ number_format($mouvement->entree > 0 ? $mouvement->entree : $mouvement->sortie, 2, ',', ' ') }}
                            </p>
                        </div>
                    </div>

                    @if ($mouvement->date_expiration)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label text-muted small">Date d'Expiration</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-warning text-dark">
                                        {{ \Carbon\Carbon::parse($mouvement->date_expiration)->format('d/m/Y') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>Article
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label text-muted small">Nom</label>
                            <p class="form-control-plaintext fw-bold">{{ $mouvement->article->nom }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">ID Article</label>
                            <p class="form-control-plaintext">{{ $mouvement->id_article }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Unité</label>
                            <p class="form-control-plaintext">{{ $mouvement->article->unite->libelle }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label text-muted small">Catégorie</label>
                            <p class="form-control-plaintext">{{ $mouvement->article->categorie->libelle }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Localisation et Stock -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-geo-alt me-2"></i>Localisation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label text-muted small">Emplacement</label>
                            <p class="form-control-plaintext fw-bold">{{ $mouvement->emplacement->libelle }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label text-muted small">Département</label>
                            <p class="form-control-plaintext">{{ $mouvement->emplacement->departement->libelle }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-diagram-2 me-2"></i>Stock
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label text-muted small">Quantité en Stock</label>
                            <p class="form-control-plaintext fw-bold fs-5">
                                {{ number_format($mouvement->stock->quantite, 2, ',', ' ') }}
                                {{ $mouvement->article->unite->libelle }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <a href="{{ route('stock.show', $mouvement->id_stock) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye me-2"></i>Voir le Stock
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Source du Mouvement -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-arrow-left-right me-2"></i>Source du Mouvement
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @if ($mouvement->id_bonReception)
                    <div class="col-lg-6">
                        <label class="form-label text-muted small">Bon de Réception</label>
                        <p class="form-control-plaintext">
                            <a href="{{ route('bon-reception.show', $mouvement->id_bonReception) }}"
                                class="text-decoration-none">
                                <strong>{{ $mouvement->bonReception->id_bonReception }}</strong>
                            </a>
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label text-muted small">Date Réception</label>
                        <p class="form-control-plaintext">{{ $mouvement->bonReception->date_->format('d/m/Y') }}</p>
                    </div>
                @elseif ($mouvement->id_bonCommande)
                    <div class="col-lg-6">
                        <label class="form-label text-muted small">Bon de Commande</label>
                        <p class="form-control-plaintext">
                            <a href="{{ route('bon-commande.show', $mouvement->id_bonCommande) }}"
                                class="text-decoration-none">
                                <strong>{{ $mouvement->bonCommande->id_bonCommande }}</strong>
                            </a>
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label text-muted small">Date Commande</label>
                        <p class="form-control-plaintext">{{ $mouvement->bonCommande->date_->format('d/m/Y') }}</p>
                    </div>
                @else
                    <div class="col-12">
                        <p class="text-muted">Mouvement manuel (pas de source liée)</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
