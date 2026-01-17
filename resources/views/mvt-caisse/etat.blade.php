@extends('layouts.app')

@section('title', 'État Financier - Rapport des Caisses')

@section('header-buttons')
    <div class="d-flex gap-2">
        <button class="btn btn-info" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Imprimer
        </button>
        <a href="{{ route('mvt-caisse.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    <!-- Résumé Global -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-primary mb-2">
                        <i class="bi bi-safe2 me-2"></i>Solde Total des Caisses
                    </h6>
                    <h3 class="text-primary mb-0">
                        {{ number_format($totalCaisses, 2, ',', ' ') }} Ar
                    </h3>
                    <small class="text-muted d-block mt-2">{{ $caisses->count() }} caisse(s) active(s)</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-info mb-2">
                        <i class="bi bi-arrow-left-right me-2"></i>Total des Mouvements
                    </h6>
                    <div class="row g-0 mt-2">
                        <div class="col-6">
                            <small class="text-danger d-block">Débits</small>
                            <strong class="text-danger">{{ number_format($mouvementsTotal->total_debit ?? 0, 2, ',', ' ') }} Ar</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-success d-block">Crédits</small>
                            <strong class="text-success">{{ number_format($mouvementsTotal->total_credit ?? 0, 2, ',', ' ') }} Ar</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détail des Caisses -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-safe2 me-2"></i>Détail des Caisses
            </h5>
        </div>
        <div class="card-body">
            @if($caisses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID Caisse</th>
                                <th class="text-end">Solde</th>
                                <th>Statut</th>
                                <th>Créée le</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($caisses as $caisse)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $caisse->id_caisse }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success fs-6">
                                            {{ number_format($caisse->montant, 2, ',', ' ') }} Ar
                                        </span>
                                    </td>
                                    <td>
                                        @if($caisse->montant > 0)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-warning">Vide</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $caisse->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('caisse.show', $caisse->id_caisse) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucune caisse trouvée</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-safe2 me-2"></i>Nombre de Caisses
                    </h6>
                    <h3 class="text-primary mb-0">{{ $caisses->count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-dash-circle me-2"></i>Total Débits
                    </h6>
                    <h3 class="text-danger mb-0">
                        {{ number_format($mouvementsTotal->total_debit ?? 0, 2, ',', ' ') }} Ar
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted mb-2">
                        <i class="bi bi-plus-circle me-2"></i>Total Crédits
                    </h6>
                    <h3 class="text-success mb-0">
                        {{ number_format($mouvementsTotal->total_credit ?? 0, 2, ',', ' ') }} Ar
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Note pour impression -->
    <div class="alert alert-info" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Note:</strong> Ce rapport affiche l'état financier de toutes les caisses. Pour plus de détails sur les mouvements, veuillez consulter la liste des mouvements ou les détails de chaque caisse.
    </div>

    <style media="print">
        .btn, .alert, .btn-group { display: none !important; }
        body { background: white; }
        .card { box-shadow: none !important; border: 1px solid #ddd; }
    </style>
@endsection
