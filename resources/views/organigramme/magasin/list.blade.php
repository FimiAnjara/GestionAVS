@extends('layouts.app')

@section('title', 'Liste des Magasins')

@section('header-buttons')
    <a href="{{ route('magasin.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Nouveau Magasin
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
            <form method="GET" action="{{ route('magasin.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-3">
                    <label for="nom" class="form-label">Nom du Magasin</label>
                    <input type="text" class="form-control form-control-sm" id="nom" name="nom"
                        placeholder="Chercher par nom..." value="{{ request('nom') }}">
                </div>

                <div class="col-lg-2">
                    <label for="id_groupe" class="form-label">Groupe</label>
                    <select class="form-select form-select-sm" id="id_groupe" name="id_groupe">
                        <option value="">-- Tous --</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id_groupe }}" @selected(request('id_groupe') == $groupe->id_groupe)>
                                {{ $groupe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label for="id_entite" class="form-label">Entité</label>
                    <select class="form-select form-select-sm" id="id_entite" name="id_entite">
                        <option value="">-- Toutes --</option>
                        @foreach ($entites as $entite)
                            <option value="{{ $entite->id_entite }}" @selected(request('id_entite') == $entite->id_entite)>
                                {{ $entite->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <label for="id_site" class="form-label">Site</label>
                    <select class="form-select form-select-sm" id="id_site" name="id_site">
                        <option value="">-- Tous --</option>
                        @foreach ($sites as $site)
                            <option value="{{ $site->id_site }}" @selected(request('id_site') == $site->id_site)>
                                {{ $site->localisation }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('magasin.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser les filtres">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Magasins -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Tous les Magasins ({{ $magasins->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($magasins->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Site & Entité</th>
                                <th class="text-center">Latitude</th>
                                <th class="text-center">Longitude</th>
                                <th>Localisation GPS</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($magasins as $magasin)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $magasin->nom }}</strong><br>
                                        <small class="text-muted">{{ $magasin->id_magasin }}</small>
                                    </td>
                                    <td>
                                        <div><strong>{{ $magasin->site?->localisation ?? 'N/A' }}</strong></div>
                                        <div class="small text-muted">{{ $magasin->site?->entite?->nom ?? 'N/A' }}</div>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($magasin->latitude, 4) }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($magasin->longitude, 4) }}
                                    </td>
                                    <td>
                                        <a href="https://maps.google.com/?q={{ $magasin->latitude }},{{ $magasin->longitude }}"
                                            target="_blank" class="text-decoration-none">
                                            <i class="bi bi-geo-alt"></i> Voir sur Google Maps
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('magasin.show', $magasin->id_magasin) }}"
                                                class="btn btn-info" title="Voir détails">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('magasin.edit', $magasin->id_magasin) }}"
                                                class="btn btn-warning" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmModal"
                                                data-bs-url="{{ route('magasin.destroy', $magasin->id_magasin) }}"
                                                data-bs-item="{{ $magasin->nom }}"
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

                <!-- Pagination -->
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Affichage de {{ $magasins->firstItem() }} à {{ $magasins->lastItem() }} sur {{ $magasins->total() }} magasins
                    </small>
                    <nav aria-label="pagination">
                        {{ $magasins->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun magasin trouvé</p>
                    <a href="{{ route('magasin.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>Créer un magasin
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
