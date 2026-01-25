@extends('layouts.app')

@section('title', 'Détails du Bon de Réception - ' . $bonReception->id_bonReception)

@section('header-buttons')
    <a href="{{ route('bon-reception.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
    <a href="{{ route('bon-reception.exportPdf', $bonReception->id_bonReception) }}" class="btn btn-success" target="_blank">
        <i class="bi bi-file-pdf me-2"></i>Exporter PDF
    </a>
    
    @if ($bonReception->etat == 1)
        <form action="{{ route('bon-reception.valider', $bonReception->id_bonReception) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i>Valider
            </button>
        </form>
        <form action="{{ route('bon-reception.annuler', $bonReception->id_bonReception) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-x-circle me-2"></i>Annuler
            </button>
        </form>
    @elseif ($bonReception->etat == 11)
        <a href="{{ route('mvt-stock.create', ['from_bon_reception' => $bonReception->id_bonReception, 'id_type_mvt' => 'E']) }}" class="btn btn-info">
            <i class="bi bi-arrow-repeat me-2"></i>Générer Mouvement de Stock
        </a>
        <form action="{{ route('bon-reception.annuler', $bonReception->id_bonReception) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-x-circle me-2"></i>Annuler
            </button>
        </form>
    @elseif ($bonReception->etat == 0)
        <span class="badge bg-danger fs-6">Annulé</span>
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
                            <p class="form-control-plaintext fw-bold text-primary">{{ $bonReception->id_bonReception }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Date</label>
                            <p class="form-control-plaintext">{{ $bonReception->date_->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">État</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-{{ $bonReception->etat_badge }} fs-6">
                                    {{ $bonReception->etat_label }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Bon Commande</label>
                            <p class="form-control-plaintext">
                                <a href="{{ route('bon-commande.show', $bonReception->bonCommande->id_bonCommande) }}"
                                    class="text-decoration-none">
                                    {{ $bonReception->bonCommande->id_bonCommande }}
                                </a>
                            </p>
                        </div>
                    </div>

                    @if($bonReception->magasin)
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label text-muted small">Magasin Destination</label>
                            <p class="form-control-plaintext fw-bold">
                                <i class="bi bi-shop me-2 text-primary"></i>{{ $bonReception->magasin->nom }}
                                <br>
                                <small class="text-muted fw-normal">{{ $bonReception->magasin->site?->localisation }}</small>
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
                    <form action="{{ route('bon-reception.destroy', $bonReception->id_bonReception) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bon ?')">
                            <i class="bi bi-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>Articles Reçus ({{ $bonReception->bonReceptionFille->count() }})
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($bonReception->bonReceptionFille->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">Photo</th>
                                <th>Article</th>
                                <th class="text-center">Quantité</th>
                                <th>Date Expiration</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bonReception->bonReceptionFille as $article)
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
                                        <div>
                                            <strong class="d-block">{{ $article->article->nom }}</strong>
                                            <small class="text-muted">{{ $article->id_article }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ number_format($article->quantite, 2, ',', ' ') }}</strong>
                                        <span class="badge bg-success ms-1">{{ $article->article->unite->libelle }}</span>
                                    </td>
                                    <td>
                                        @if ($article->date_expiration)
                                            <span class="badge bg-warning text-dark">
                                                {{ \Carbon\Carbon::parse($article->date_expiration)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('articles.show', $article->id_article) }}"
                                            class="btn btn-sm btn-info" title="Voir l'article">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
