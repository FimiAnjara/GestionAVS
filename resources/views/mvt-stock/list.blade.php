@extends('layouts.app')

@section('title', 'Mouvements de Stock')

@section('content')
    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i>Filtres et Recherche
            </h6>
        </div>
        <div class="card-body p-3">
            <form method="GET" action="{{ route('mvt-stock.list') }}" class="row g-2 align-items-end">
                <div class="col-lg-2">
                    <label for="id" class="form-label">ID Mouvement</label>
                    <input type="text" class="form-control form-control-sm" id="id" name="id"
                        placeholder="MVT_..." value="{{ request('id') }}">
                </div>

                <div class="col-lg-2">
                    <label for="date_from" class="form-label">De</label>
                    <input type="date" class="form-control form-control-sm" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>

                <div class="col-lg-2">
                    <label for="date_to" class="form-label">À</label>
                    <input type="date" class="form-control form-control-sm" id="date_to" name="date_to"
                        value="{{ request('date_to') }}">
                </div>

                <div class="col-lg-3">
                    <label for="id_article" class="form-label">Article</label>
                    <select class="form-select form-select-sm select2-select" id="id_article" name="id_article">
                        <option value="">-- Tous --</option>
                        @foreach ($articles as $article)
                            <option value="{{ $article->id_article }}"
                                {{ request('id_article') == $article->id_article ? 'selected' : '' }}>
                                {{ $article->id_article }} - {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary btn-sm" title="Réinitialiser les filtres">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Mouvements -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-list me-2"></i>Mouvements de Stock ({{ $mouvements->total() }} total)
            </h6>
        </div>
        <div class="card-body p-0">
            @if ($mouvements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Article</th>
                                <th class="text-center">Entrée</th>
                                <th class="text-center">Sortie</th>
                                <th>Emplacement</th>
                                <th>Expiration</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mouvements as $mvt)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $mvt->id_mvt_stock }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $mvt->date_->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div>
                                            <strong class="d-block">{{ $mvt->article->nom }}</strong>
                                            <small class="text-muted">{{ $mvt->id_article }}</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($mvt->entree > 0)
                                            <span class="badge bg-success">{{ number_format($mvt->entree, 2, ',', ' ') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($mvt->sortie > 0)
                                            <span class="badge bg-danger">{{ number_format($mvt->sortie, 2, ',', ' ') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $mvt->emplacement->libelle }}</small>
                                    </td>
                                    <td>
                                        @if ($mvt->date_expiration)
                                            <span class="badge bg-warning text-dark">
                                                {{ \Carbon\Carbon::parse($mvt->date_expiration)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('mvt-stock.show', $mvt->id_mvt_stock) }}"
                                            class="btn btn-info btn-sm" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('mvt-stock.exportPdf', $mvt->id_mvt_stock) }}"
                                            class="btn btn-success btn-sm" title="Exporter PDF" target="_blank">
                                            <i class="bi bi-file-pdf"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-3 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Affichage de {{ $mouvements->firstItem() }} à {{ $mouvements->lastItem() }} sur {{ $mouvements->total() }} mouvements
                    </small>
                    <nav aria-label="pagination">
                        {{ $mouvements->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun mouvement de stock trouvé</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.select2-select').select2({
                    language: 'fr',
                    width: '100%'
                });
            });
        </script>
    @endpush
@endsection
