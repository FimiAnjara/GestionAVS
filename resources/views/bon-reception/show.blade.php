@extends('layouts.app')

@section('title', 'Détails du Bon de Réception - ' . $bonReception->id_bonReception)

@section('header-buttons')
    <a href="{{ route('bon-reception.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
    <a href="{{ route('bon-reception.exportPdf', $bonReception->id_bonReception) }}" class="btn btn-success" target="_blank">
        <i class="bi bi-file-pdf me-2"></i>Exporter PDF
    </a>
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
                    @if ($bonReception->etat == 1)
                        <form action="{{ route('bon-reception.recevoir', $bonReception->id_bonReception) }}"
                            method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Confirmer la réception et générer les mouvements de stock ?')">
                                <i class="bi bi-check-circle me-2"></i>Réceptionner
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info mb-2">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>Ce bon est déjà réceptionné</small>
                        </div>
                    @endif

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
                <i class="bi bi-box-seam me-2"></i>Articles Reçus ({{ $bonReception->articles->count() }})
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($bonReception->articles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Article</th>
                                <th class="text-center">Quantité</th>
                                <th>Date Expiration</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bonReception->articles as $article)
                                <tr>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $article->article->nom }}</strong>
                                            <small class="text-muted">{{ $article->id_article }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ number_format($article->quantite, 2, ',', ' ') }}</strong>
                                        <small class="text-muted">{{ $article->article->unite->libelle }}</small>
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

    <!-- Mouvements de Stock Générés -->
    @if ($bonReception->mouvementsStock()->count() > 0)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="mb-0">
                    <i class="bi bi-arrow-left-right me-2"></i>Mouvements de Stock Générés
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>ID Mouvement</th>
                                <th>Article</th>
                                <th class="text-center">Entrée</th>
                                <th>Emplacement</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bonReception->mouvementsStock as $mvt)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $mvt->id_mvt_stock }}</strong>
                                    </td>
                                    <td>{{ $mvt->article->nom }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ number_format($mvt->entree, 2, ',', ' ') }}</span>
                                    </td>
                                    <td>{{ $mvt->emplacement->libelle }}</td>
                                    <td><small>{{ $mvt->date_->format('d/m/Y H:i') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
