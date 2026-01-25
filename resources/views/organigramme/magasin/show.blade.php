@extends('layouts.app')

@section('title', 'Détails - ' . $magasin->nom)

@section('header-buttons')
    <a href="{{ route('magasin.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
    <a href="{{ route('magasin.edit', $magasin->id_magasin) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Modifier
    </a>
@endsection

@section('content')
    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="magasinTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                <i class="bi bi-info-circle me-2"></i>Info générale
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="evaluation-tab" data-bs-toggle="tab" data-bs-target="#evaluation" type="button" role="tab" aria-controls="evaluation" aria-selected="false">
                <i class="bi bi-calculator me-2"></i>Evaluation stock
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="magasinTabsContent">
        
        <!-- Tab 1: Info générale -->
        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0">
                                <i class="bi bi-shop me-2"></i>Informations du Magasin
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted small">ID</label>
                                <p class="form-control-plaintext fw-bold text-primary">{{ $magasin->id_magasin }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Nom</label>
                                <p class="form-control-plaintext fw-bold">{{ $magasin->nom }}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Latitude</label>
                                        <p class="form-control-plaintext">{{ number_format($magasin->latitude, 6) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Longitude</label>
                                        <p class="form-control-plaintext">{{ number_format($magasin->longitude, 6) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Créé le</label>
                                <p class="form-control-plaintext">{{ $magasin->created_at->format('d/m/Y à H:i') }}</p>
                            </div>

                            @if($magasin->updated_at !== $magasin->created_at)
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Modifié le</label>
                                    <p class="form-control-plaintext">{{ $magasin->updated_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0">
                                <i class="bi bi-geo-alt me-2"></i>Localisation GPS
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Coordonnées GPS pour les cartes numériques
                            </div>

                            <div class="mb-3">
                                <a href="https://maps.google.com/?q={{ $magasin->latitude }},{{ $magasin->longitude }}"
                                    target="_blank" class="btn btn-primary w-100">
                                    <i class="bi bi-geo-alt me-2"></i>Voir sur Google Maps
                                </a>
                            </div>

                            <div class="mb-3">
                                <a href="{{ route('magasin.carte') }}?nom={{ urlencode($magasin->nom) }}"
                                    class="btn btn-info w-100">
                                    <i class="bi bi-map me-2"></i>Voir sur notre Carte
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h6 class="mb-0">
                                <i class="bi bi-gear me-2"></i>Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('magasin.destroy', $magasin->id_magasin) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce magasin ?')">
                                    <i class="bi bi-trash me-2"></i>Supprimer ce Magasin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Evaluation stock -->
        <div class="tab-pane fade" id="evaluation" role="tabpanel" aria-labelledby="evaluation-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-list-check me-2"></i>Détails des Mouvements et Evaluation
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($mouvements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>ID Mouvement</th>
                                        <th>Article</th>
                                        <th>Unité</th>
                                        <th class="text-center">Entrée</th>
                                        <th class="text-center">Sortie</th>
                                        <th class="text-center text-primary">Reste</th>
                                        <th class="text-end">Prix Unit.</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Type Eval</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mouvements as $mvt)
                                        <tr>
                                            <td>{{ $mvt->mvtStock->date_?->format('d/m/Y') ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('mvt-stock.show', $mvt->id_mvt_stock) }}">
                                                    {{ $mvt->id_mvt_stock }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>{{ $mvt->article?->designation ?? $mvt->id_article }}</strong>
                                            </td>
                                            <td>
                                                {{ $mvt->article?->unite?->libelle ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if($mvt->entree > 0)
                                                    <span class="badge bg-success">+{{ number_format($mvt->entree, 0) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($mvt->sortie > 0)
                                                    <span class="badge bg-danger">-{{ number_format($mvt->sortie, 0) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center fw-bold text-primary">
                                                {{ number_format($mvt->reste, 0) }}
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($mvt->prix_unitaire, 0, ',', ' ') }} Ar
                                            </td>
                                            <td class="text-end fw-bold">
                                                @php 
                                                    $qty = ($mvt->entree > 0) ? $mvt->entree : $mvt->sortie;
                                                    $total = $qty * $mvt->prix_unitaire;
                                                @endphp
                                                {{ number_format($total, 0, ',', ' ') }} Ar
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $typeEval = $mvt->article?->typeEvaluation?->libelle ?? 'Non défini';
                                                    $badgeClass = match($typeEval) {
                                                        'CMUP' => 'bg-primary',
                                                        'FIFO' => 'bg-info text-dark',
                                                        'LIFO' => 'bg-warning text-dark',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $typeEval }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $mouvements->links() }}
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <p>Aucun mouvement de stock pour ce magasin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
