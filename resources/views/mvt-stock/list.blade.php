@extends('layouts.app')

@section('title', 'Mouvements de Stock')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('mvt-stock.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nouveau Mouvement
        </a>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-3">
                <i class="bi bi-arrow-left-right me-2" style="color: #0056b3;"></i>
                Mouvements de Stock
            </h5>
            <!-- Filtres -->
            <form class="row g-2" method="GET" action="{{ route('mvt-stock.list') }}">
                <div class="col-md-2">
                    <input type="text" name="id_mvt_stock" class="form-control form-control-sm" placeholder="ID Mvt" value="{{ request('id_mvt_stock') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <select name="id_magasin" class="form-select form-select-sm">
                        <option value="">-- Tous les magasins --</option>
                        @foreach($magasins as $mag)
                            <option value="{{ $mag->id_magasin }}" @selected(request('id_magasin') == $mag->id_magasin)>
                                {{ $mag->nom ?? $mag->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="description" class="form-control form-control-sm" placeholder="Description" value="{{ request('description') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            @if($mouvements->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="mvtTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%"></th>
                            <th>ID Mouvement</th>
                            <th>Date</th>
                            <th>Magasin</th>
                            <th>Description</th>
                            <th class="text-end">Montant Total</th>
                            <th class="text-center">Articles</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mouvements as $mvt)
                        <tr class="mvt-row" data-mvt-id="{{ $mvt->id_mvt_stock }}">
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary toggle-details" 
                                        title="Voir/Masquer les articles">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </td>
                            <td><strong>{{ $mvt->id_mvt_stock }}</strong></td>
                            <td>{{ $mvt->date_?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                            <td>{{ $mvt->magasin?->nom ?? $mvt->magasin?->designation ?? 'N/A' }}</td>
                            <td>{{ Str::limit($mvt->description, 30) ?? '-' }}</td>
                            <td class="text-end">
                                <span class="badge bg-info">{{ number_format($mvt->montant_total, 0) }} Ar</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $mvt->mvtStockFille?->count() ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('mvt-stock.show', $mvt->id_mvt_stock) }}" class="btn btn-sm btn-info" title="Voir les détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <!-- Ligne détails (cachée par défaut) -->
                        <tr class="details-row" data-mvt-id="{{ $mvt->id_mvt_stock }}" style="display: none;">
                            <td colspan="8">
                                <div class="p-3 bg-light">
                                    <h6 class="mb-3"><i class="bi bi-list-check me-2"></i>Articles du mouvement</h6>
                                    @if($mvt->mvtStockFille->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Article</th>
                                                    <th class="text-center">Entrée</th>
                                                    <th class="text-center">Sortie</th>
                                                    <th class="text-center">Prix Unit.</th>
                                                    <th class="text-end">Montant (Ar)</th>
                                                    <th>Date Exp.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($mvt->mvtStockFille as $fille)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $fille->article?->id_article }}</strong><br>
                                                        <small class="text-muted">{{ $fille->article?->designation }}</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success">{{ $fille->entree }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-danger">{{ $fille->sortie }}</span>
                                                    </td>
                                                    <td class="text-center">{{ number_format($fille->prix_unitaire, 0) }} Ar</td>
                                                    <td class="text-end">
                                                        <strong>{{ number_format($fille->entree * $fille->prix_unitaire, 0) }} Ar</strong>
                                                    </td>
                                                    <td>
                                                        @if($fille->date_expiration)
                                                            {{ $fille->date_expiration->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>Aucun article pour ce mouvement
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-light">
                {{ $mouvements->links() }}
            </div>
            @else
            <div class="p-4 text-center text-muted">
                <i class="bi bi-inbox me-2"></i>Aucun mouvement de stock enregistré
            </div>
            @endif
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Toggle détails
        $('.toggle-details').on('click', function(e) {
            e.preventDefault();
            const mvtId = $(this).closest('.mvt-row').data('mvt-id');
            const detailsRow = $('tr.details-row[data-mvt-id="' + mvtId + '"]');
            const icon = $(this).find('i');
            
            // Vérifier l'état AVANT toggle
            const isHidden = detailsRow.is(':hidden');
            
            detailsRow.slideToggle(300);
            
            // Changer l'icône après toggle
            setTimeout(function() {
                if (isHidden) {
                    icon.removeClass('bi-plus').addClass('bi-dash');
                } else {
                    icon.removeClass('bi-dash').addClass('bi-plus');
                }
            }, 150);
        });
    });
    </script>
@endsection
