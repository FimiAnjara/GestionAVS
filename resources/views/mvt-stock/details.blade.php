@extends('layouts.app')

@section('title', 'Détails des Mouvements')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour aux mouvements
        </a>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-3">
                <i class="bi bi-list-check me-2" style="color: #0056b3;"></i>
                Détails des Mouvements (Articles)
            </h5>

            <!-- Filtres -->
            <form class="row g-2" method="GET" action="{{ route('stock.details') }}">
                <div class="col-md-2">
                    <input type="text" name="id_mvt_stock" class="form-control form-control-sm"
                        placeholder="ID Mouvement" value="{{ request('id_mvt_stock') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="id_article" class="form-control form-control-sm" placeholder="Article"
                        value="{{ request('id_article') }}">
                </div>
                <div class="col-md-1">
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-md-1">
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <select name="id_magasin" class="form-select form-select-sm">
                        <option value="">-- Tous les magasins --</option>
                        @foreach ($magasins as $mag)
                            <option value="{{ $mag->id_magasin }}" @selected(request('id_magasin') == $mag->id_magasin)>
                                {{ $mag->nom ?? $mag->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @if ($mouvementsFille->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>ID mere</th>
                                <th>Date</th>
                                <th>Magasin</th>
                                <th>Article</th>
                                <th class="text-center" width="10%">
                                    <i class="bi bi-plus-circle text-success me-1"></i>Entrée
                                </th>
                                <th class="text-center" width="10%">
                                    <i class="bi bi-dash-circle text-danger me-1"></i>Sortie
                                </th>
                                <th class="text-center" width="12%">Prix Unit. (Ar)</th>
                                <th class="text-end" width="12%">Montant (Ar)</th>
                                <th class="text-center" width="12%">Date Exp.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mouvementsFille as $fille)
                                <tr>
                                    <td>
                                        {{ $fille->id_mvt_stock_fille }}
                                    </td>
                                    <td>
                                        <a href="{{ route('mvt-stock.show', $fille->mvtStock->id_mvt_stock) }}"
                                            class="text-decoration-none fw-bold">
                                            {{ $fille->mvtStock->id_mvt_stock }}
                                        </a>
                                    </td>

                                    <td>{{ $fille->mvtStock->date_?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                    <td>
                                        <small class="badge bg-info">
                                            {{ $fille->mvtStock->magasin?->nom ?? ($fille->mvtStock->magasin?->designation ?? 'N/A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $fille->id_article }}</strong><br>
                                        <small class="text-muted">{{ $fille->article?->designation ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($fille->entree > 0)
                                            <span class="badge bg-success">{{ number_format($fille->entree, 0) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($fille->sortie > 0)
                                            <span class="badge bg-danger">{{ number_format($fille->sortie, 0) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($fille->prix_unitaire, 0) }}
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($fille->getMontantAttribute(), 0) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if ($fille->date_expiration)
                                            <small>{{ $fille->date_expiration->format('d/m/Y') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light">
                    {{ $mouvementsFille->links() }}
                </div>
            @else
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox me-2"></i>Aucun détail de mouvement trouvé
                </div>
            @endif
        </div>
    </div>
@endsection
