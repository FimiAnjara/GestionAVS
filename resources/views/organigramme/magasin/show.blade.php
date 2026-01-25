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
    @php
        $isFiltering = request()->hasAny(['id_article', 'date_from', 'date_to']);
    @endphp

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="magasinTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ !$isFiltering ? 'active' : '' }}" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="{{ !$isFiltering ? 'true' : 'false' }}">
                <i class="bi bi-info-circle me-2"></i>Info générale
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $isFiltering ? 'active' : '' }}" id="evaluation-tab" data-bs-toggle="tab" data-bs-target="#evaluation" type="button" role="tab" aria-controls="evaluation" aria-selected="{{ $isFiltering ? 'true' : 'false' }}">
                <i class="bi bi-box-seam me-2"></i>Inventaire par lot
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="financial-tab" data-bs-toggle="tab" data-bs-target="#financial" type="button" role="tab" aria-controls="financial" aria-selected="false">
                <i class="bi bi-currency-dollar me-2"></i>Évaluation financière
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content" id="magasinTabsContent">
        
        <!-- Tab 1: Info générale -->
        <div class="tab-pane fade {{ !$isFiltering ? 'show active' : '' }}" id="info" role="tabpanel" aria-labelledby="info-tab">
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

        <!-- Tab 2: Inventaire stock -->
        <div class="tab-pane fade {{ $isFiltering ? 'show active' : '' }}" id="evaluation" role="tabpanel" aria-labelledby="evaluation-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>Inventaire de stock (Mouvements)
                        </h6>
                    </div>
                    
                    <!-- Filtres -->
                    <form action="{{ route('magasin.show', $magasin->id_magasin) }}" method="GET" class="row g-2">
                        <div class="col-md-5">
                            <select name="id_article" class="form-select form-select-sm select2-article">
                                <option value="">-- Tous les produits de l'entité --</option>
                                @foreach($articles as $art)
                                    <option value="{{ $art->id_article }}" @selected(request('id_article') == $art->id_article)>
                                        {{ $art->id_article }} - {{ $art->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Du</span>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Au</span>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-filter me-1"></i>Filtrer
                            </button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('magasin.show', $magasin->id_magasin) }}" class="btn btn-sm btn-outline-secondary w-100" title="Réinitialiser">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    @if($mouvements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th class="text-center">Photo</th>
                                        <th>ID Lot/Mvt</th>
                                        <th>Article</th>
                                        <th>Unité</th>
                                        <th class="text-center">Quantité Initiale</th>
                                        <th class="text-center text-primary">En Stock (Reste)</th>
                                        <th>Date Exp.</th>
                                        <th class="text-end">Prix Unit.</th>
                                        <th class="text-end">Valeur Reste</th>
                                        <th class="text-center">Type Eval</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mouvements as $mvt)
                                        <tr>
                                            <td>{{ $mvt->mvtStock->date_?->format('d/m/Y') ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                @if($mvt->article?->photo)
                                                    <img src="{{ asset('storage/' . $mvt->article->photo) }}" 
                                                         class="rounded shadow-sm" 
                                                         style="width: 35px; height: 35px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 35px; height: 35px;">
                                                        <i class="bi bi-image text-muted" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('mvt-stock.show', $mvt->id_mvt_stock) }}" class="text-decoration-none fw-bold">
                                                    {{ $mvt->id_mvt_stock_fille }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>
                                                    <a href="{{ route('articles.show', $mvt->id_article) }}" class="text-decoration-none text-dark">
                                                        {{ $mvt->article?->nom ?? $mvt->id_article }}
                                                    </a>
                                                </strong>
                                                <br>
                                                <small class="text-muted">{{ $mvt->article?->designation }}</small>
                                            </td>
                                            <td>
                                                {{ $mvt->article?->unite?->libelle ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                <span class="text-secondary">{{ number_format($mvt->entree, 0) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $mvt->reste > 0 ? 'bg-success' : 'bg-light text-muted' }} pb-2">
                                                    {{ number_format($mvt->reste, 0) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($mvt->date_expiration)
                                                    <span class="text-{{ $mvt->date_expiration->isPast() ? 'danger' : 'dark' }} fw-bold">
                                                        {{ $mvt->date_expiration->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($mvt->prix_unitaire, 0, ',', ' ') }} Ar
                                            </td>
                                            <td class="text-end fw-bold">
                                                {{ number_format($mvt->reste * $mvt->prix_unitaire, 0, ',', ' ') }} Ar
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
                            {{ $mouvements->appends(request()->all())->links() }}
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <p>Aucun mouvement de stock correspondant pour ce magasin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab 3: Évaluation financière -->
        <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-currency-exchange me-2"></i>Valeur actuelle du stock
                    </h6>
                    <div class="h5 mb-0 text-primary fw-bold">
                        Total Magasin: {{ number_format($valeurTotaleMagasin, 0, ',', ' ') }} Ar
                    </div>
                </div>
                <div class="card-body p-0">
                    @if(count($evaluationStock) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="5%">Photo</th>
                                        <th>Article</th>
                                        <th class="text-center">Type Eval.</th>
                                        <th class="text-center">Quantité Restante</th>
                                        <th class="text-center">Unité</th>
                                        <th class="text-end">Prix Actuel (Unit.)</th>
                                        <th class="text-end">Valeur Totale</th>
                                        <th class="text-center">État</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($evaluationStock as $item)
                                        <tr>
                                            <td class="text-center">
                                                @if($item['article']->photo)
                                                    <img src="{{ asset('storage/' . $item['article']->photo) }}" 
                                                         class="rounded shadow-sm" 
                                                         style="width: 35px; height: 35px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 35px; height: 35px;">
                                                        <i class="bi bi-image text-muted" style="font-size: 0.8rem;"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $item['article']->id_article }} - {{ $item['article']->nom }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item['article']->designation }}</small>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $typeEval = $item['article']->typeEvaluation?->libelle ?? 'Non défini';
                                                    $badgeClass = match($typeEval) {
                                                        'CMUP' => 'bg-primary',
                                                        'FIFO' => 'bg-info text-dark',
                                                        'LIFO' => 'bg-warning text-dark',
                                                        default => 'bg-secondary'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $typeEval }}</span>
                                            </td>
                                            <td class="text-center fw-bold fs-6">
                                                {{ number_format($item['quantite'], 0) }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ $item['article']->unite?->libelle ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($item['prix_unitaire'], 2, ',', ' ') }} Ar
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                {{ number_format($item['valeur_totale'], 0, ',', ' ') }} Ar
                                            </td>
                                            <td class="text-center">
                                                @if($item['quantite'] > 10)
                                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>En stock</span>
                                                @elseif($item['quantite'] > 0)
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i>Stock faible</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rupture</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="6" class="text-end">VALEUR TOTALE DU STOCK :</td>
                                        <td class="text-end text-primary fs-5">{{ number_format($valeurTotaleMagasin, 0, ',', ' ') }} Ar</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            <p>Aucun stock disponible dans ce magasin.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('.select2-article').select2({
                placeholder: "-- Sélectionner un produit --",
                allowClear: true,
                theme: 'bootstrap-5'
            });

            // Persistance des onglets après filtrage
            // Si on a des paramètres de filtre dans l'URL, on active l'onglet Inventaire
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('id_article') || urlParams.has('date_from') || urlParams.has('date_to')) {
                const triggerEl = document.querySelector('#evaluation-tab');
                if (triggerEl) {
                    bootstrap.Tab.getInstance(triggerEl)?.show() || new bootstrap.Tab(triggerEl).show();
                }
            }

            // Optionnel : Gérer le hash dans l'URL pour la navigation directe
            const hash = window.location.hash;
            if (hash) {
                const targetTab = document.querySelector(`button[data-bs-target="${hash}"]`);
                if (targetTab) {
                    new bootstrap.Tab(targetTab).show();
                }
            }

            // Mettre à jour le hash de l'URL quand on change d'onglet
            $('.nav-link').on('shown.bs.tab', function (e) {
                const target = $(e.target).data('bs-target');
                history.replaceState(null, null, target);
            });
        });
    </script>
@endsection
