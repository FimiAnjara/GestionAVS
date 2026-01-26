@extends('layouts.app')

@section('title', 'Détails du Bon de Livraison Client - ' . $bonLivraison->id_bon_livraison_client)

@section('header-buttons')
    <a href="{{ route('bon-livraison-client.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
    <a href="{{ route('bon-livraison-client.exportPdf', $bonLivraison->id_bon_livraison_client) }}" class="btn btn-success" target="_blank">
        <i class="bi bi-file-pdf me-2"></i>Exporter PDF
    </a>
    
    @if ($bonLivraison->etat == 1)
        <a href="{{ route('bon-livraison-client.valider', $bonLivraison->id_bon_livraison_client) }}" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Valider et Sortie Stock
        </a>
    @elseif ($bonLivraison->etat == 2)
        <span class="badge bg-success fs-6"><i class="bi bi-check2-all me-1"></i>Déjà Livré (Stock Sorti)</span>
    @endif
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-file-earmark-check me-2"></i>Informations Générales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">ID</label>
                            <p class="form-control-plaintext fw-bold text-primary">{{ $bonLivraison->id_bon_livraison_client }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Date</label>
                            <p class="form-control-plaintext">{{ $bonLivraison->date_->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">État</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $bonLivraison->etat == 1 ? 'warning' : 'success' }} fs-6">
                                    {{ $bonLivraison->etat == 1 ? 'Créée' : 'Livré (Stock Sorti)' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Bon Commande</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('bon-commande-client.show', $bonLivraison->id_bon_commande_client) }}"
                                    class="text-decoration-none">
                                    {{ $bonLivraison->id_bon_commande_client }}
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($bonLivraison->magasin)
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Magasin Source</label>
                            <p class="form-control-plaintext fw-bold">
                                <i class="bi bi-shop me-2 text-primary"></i>{{ $bonLivraison->magasin->nom }}
                                <br>
                                <small class="text-muted fw-normal">{{ $bonLivraison->magasin->site?->localisation }}</small>
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
                        <i class="bi bi-gear me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-danger w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteConfirmModal"
                        data-bs-url="{{ route('bon-livraison-client.destroy', $bonLivraison->id_bon_livraison_client) }}"
                        data-bs-item="le bon de livraison {{ $bonLivraison->id_bon_livraison_client }}"
                        title="Supprimer">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>Articles à Livrer ({{ $bonLivraison->bonLivraisonClientFille->count() }})
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($bonLivraison->bonLivraisonClientFille->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">Photo</th>
                                <th>Article</th>
                                <th class="text-center">Quantité</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bonLivraison->bonLivraisonClientFille as $item)
                                <tr>
                                    <td class="text-center">
                                        @if($item->article->photo)
                                            <img src="{{ asset('storage/' . $item->article->photo) }}" 
                                                 class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $item->article->nom }}</strong>
                                            <small class="text-muted">{{ $item->id_article }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ number_format($item->quantite, 2, ',', ' ') }}</strong>
                                        <span class="badge bg-success ms-1">{{ $item->article->unite->libelle ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('articles.show', $item->id_article) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun article dans ce bon</p>
                </div>
            @endif
        </div>
    </div>
@endsection
