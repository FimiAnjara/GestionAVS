@extends('layouts.app')

@section('title', 'État du Stock - Derniers Mouvements')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
    border: 1px solid #dee2e6;
}
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 12px;
    line-height: 36px;
}
.select2-container--bootstrap-5 .select2-dropdown {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.5rem;
}
.select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
    background-color: #0d6efd;
}
.movement-row:hover {
    background-color: #f8f9fa;
}
</style>
@endpush

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour aux mouvements
        </a>
    </div>
@endsection

@section('content')
<!-- Filtres de recherche -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light border-0 py-3">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filtres de recherche
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('stock.etat') }}" class="row g-3">
            <div class="col-md-4">
                <label for="entite_id" class="form-label">Entité</label>
                <select name="entite_id" id="entite_id" class="form-select select2" data-placeholder="Rechercher une entité...">
                    <option value="">Toutes les entités</option>
                    @foreach($entites as $entite)
                        <option value="{{ $entite->id_entite }}" {{ request('entite_id') == $entite->id_entite ? 'selected' : '' }}>
                            {{ $entite->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="magasin_id" class="form-label">Magasin</label>
                <select name="magasin_id" id="magasin_id" class="form-select select2" data-placeholder="Rechercher un magasin...">
                    <option value="">Tous les magasins</option>
                    @foreach($allMagasins as $magasin)
                        <option value="{{ $magasin->id_magasin }}" 
                                data-entite="{{ $magasin->site && $magasin->site->entite ? $magasin->site->entite->id_entite : '' }}"
                                {{ request('magasin_id') == $magasin->id_magasin ? 'selected' : '' }}>
                            {{ $magasin->nom ?? $magasin->designation }}
                            @if($magasin->site && $magasin->site->entite)
                                - {{ $magasin->site->entite->nom }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Rechercher
                </button>
                <a href="{{ route('stock.etat') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des 10 derniers mouvements -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2" style="color: #0056b3;"></i>
                10 Derniers Mouvements de Stock
                @if(request()->hasAny(['entite_id', 'magasin_id']))
                    <small class="text-muted">(Filtré)</small>
                @endif
            </h5>
            <span class="badge bg-primary fs-6">{{ count($derniersMovements) }} mouvement(s)</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($derniersMovements && count($derniersMovements) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Date</th>
                            <th>Article</th>
                            <th>Magasin</th>
                            <th>Entité</th>
                            <th class="text-center">
                                <i class="bi bi-plus-circle text-success me-1"></i>Entrée
                            </th>
                            <th class="text-center">
                                <i class="bi bi-dash-circle text-danger me-1"></i>Sortie
                            </th>
                            <th class="text-center">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($derniersMovements as $mvt)
                            <tr class="movement-row">
                                <td class="ps-3">
                                    <small class="text-muted">
                                        {{ $mvt->created_at ? $mvt->created_at->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $mvt->article->designation ?? $mvt->id_article }}</strong>
                                    <br><small class="text-muted">{{ $mvt->id_article }}</small>
                                </td>
                                <td>
                                    {{ $mvt->mvtStock && $mvt->mvtStock->magasin ? ($mvt->mvtStock->magasin->nom ?? $mvt->mvtStock->magasin->designation) : '-' }}
                                </td>
                                <td>
                                    <small>
                                        {{ $mvt->mvtStock && $mvt->mvtStock->magasin && $mvt->mvtStock->magasin->site && $mvt->mvtStock->magasin->site->entite 
                                            ? $mvt->mvtStock->magasin->site->entite->nom 
                                            : '-' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($mvt->entree > 0)
                                        <span class="badge bg-success">+{{ number_format($mvt->entree, 0) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($mvt->sortie > 0)
                                        <span class="badge bg-danger">-{{ number_format($mvt->sortie, 0) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($mvt->entree > 0)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-arrow-down-circle me-1"></i>Entrée
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-arrow-up-circle me-1"></i>Sortie
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted mb-0">
                    @if(request()->hasAny(['entite_id', 'magasin_id']))
                        Aucun mouvement ne correspond aux critères de recherche
                    @else
                        Aucun mouvement de stock enregistré
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialiser Select2 pour les filtres avec recherche
    $('#entite_id').select2({
        theme: 'bootstrap-5',
        allowClear: true,
        width: '100%',
        placeholder: 'Rechercher une entité...',
        language: {
            noResults: function() {
                return "Aucun résultat trouvé";
            },
            searching: function() {
                return "Recherche en cours...";
            },
            inputTooShort: function() {
                return "Tapez pour rechercher...";
            }
        }
    });
    
    $('#magasin_id').select2({
        theme: 'bootstrap-5',
        allowClear: true,
        width: '100%',
        placeholder: 'Rechercher un magasin...',
        language: {
            noResults: function() {
                return "Aucun résultat trouvé";
            },
            searching: function() {
                return "Recherche en cours...";
            },
            inputTooShort: function() {
                return "Tapez pour rechercher...";
            }
        }
    });
    
    // Filtrer les magasins par entité quand une entité est sélectionnée
    $('#entite_id').on('change', function() {
        var selectedEntite = $(this).val();
        var magasinSelect = $('#magasin_id');
        
        // Réinitialiser le magasin sélectionné
        magasinSelect.val('').trigger('change');
        
        // Afficher/masquer les options de magasin selon l'entité
        magasinSelect.find('option').each(function() {
            var entiteId = $(this).data('entite');
            if (!selectedEntite || entiteId == selectedEntite || $(this).val() === '') {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    });
});
</script>
@endpush
