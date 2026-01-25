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
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}">
                </div>
                <div class="col-md-4">
                    <select name="id_magasin" class="form-select form-select-sm">
                        <option value="">-- Tous les magasins --</option>
                        @foreach ($magasins as $magasin)
                            <option value="{{ $magasin->id_magasin }}" {{ request('id_magasin') == $magasin->id_magasin ? 'selected' : '' }}>
                                [{{ $magasin->site?->entite?->nom ?? 'N/A' }}] {{ $magasin->site?->localisation ?? 'N/A' }} - {{ $magasin->nom ?? $magasin->designation }}
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
                                <th width="5%">Photo</th>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Magasin</th>
                                <th>Article</th>
                                <th class="text-center">Unité</th>
                                <th class="text-center" width="8%">
                                    <i class="bi bi-plus-circle text-success me-1"></i>Entrée
                                </th>
                                <th class="text-center" width="8%">
                                    <i class="bi bi-dash-circle text-danger me-1"></i>Sortie
                                </th>
                                <th class="text-center" width="8%">Reste</th>
                                <th class="text-center" width="10%">Prix Unit. (Ar)</th>
                                <th class="text-end" width="10%">Montant (Ar)</th>
                                <th class="text-center" width="10%">Date Exp.</th>
                                <th class="text-center" width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mouvementsFille as $fille)
                                <tr>
                                    <td class="text-center">
                                        @if($fille->article->photo)
                                            <img src="{{ asset('storage/' . $fille->article->photo) }}" 
                                                 class="rounded shadow-sm" 
                                                 style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 30px; height: 30px;">
                                                <i class="bi bi-image text-muted" style="font-size: 0.8rem;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="fw-bold">{{ $fille->id_mvt_stock_fille }}</small>
                                    </td>
                                    <td><small>{{ $fille->mvtStock->date_?->format('d/m/Y') ?? 'N/A' }}</small></td>
                                    <td>
                                        <small class="badge bg-light text-dark border">
                                            {{ $fille->mvtStock->magasin?->nom ?? ($fille->mvtStock->magasin?->designation ?? 'N/A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('articles.show', $fille->id_article) }}" class="text-decoration-none fw-bold small">
                                            {{ $fille->id_article }}
                                        </a>
                                        <br>
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">{{ Str::limit($fille->article?->designation ?? '-', 20) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ $fille->article->unite->libelle ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($fille->entree > 0)
                                            <span class="text-success fw-bold">{{ number_format($fille->entree, 0) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($fille->sortie > 0)
                                            <span class="text-danger fw-bold">{{ number_format($fille->sortie, 0) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($fille->entree > 0)
                                            <span class="badge {{ $fille->reste > 0 ? 'bg-success' : 'bg-light text-muted' }}">
                                                {{ number_format($fille->reste, 0) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center small">
                                        {{ number_format($fille->prix_unitaire, 0) }}
                                    </td>
                                    <td class="text-end small">
                                        <strong>{{ number_format($fille->getMontantAttribute(), 0) }}</strong>
                                    </td>
                                    <td class="text-center small">
                                        @if ($fille->date_expiration)
                                            <span class="{{ $fille->date_expiration->isPast() ? 'text-danger fw-bold' : '' }}">
                                                {{ $fille->date_expiration->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('mvt-stock.editFille', $fille->id_mvt_stock_fille) }}" class="btn btn-sm btn-outline-primary border-0 p-1" title="Modifier cette ligne">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('mvt-stock.destroyFille', $fille->id_mvt_stock_fille) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" 
                                                    onclick="return confirm('Supprimer cette ligne de mouvement ?')" title="Supprimer">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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
