@extends('layouts.app')

@section('title', 'Détails du Mouvement de Stock')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-arrow-left-right"></i> Mouvement {{ $mouvement->id_mvt_stock }}</h4>
                    <a href="{{ route('mvt-stock.list') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informations du mouvement -->
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4"><strong>ID Mouvement</strong></dt>
                                <dd class="col-sm-8">{{ $mouvement->id_mvt_stock }}</dd>

                                <dt class="col-sm-4"><strong>Date</strong></dt>
                                <dd class="col-sm-8">{{ $mouvement->date_?->format('d/m/Y H:i') ?? 'N/A' }}</dd>

                                <dt class="col-sm-4"><strong>Magasin</strong></dt>
                                <dd class="col-sm-8">
                                    {{ $mouvement->magasin?->libelle ?? $mouvement->magasin?->designation ?? 'N/A' }}
                                </dd>
                            </dl>
                        </div>

                        <!-- Montant -->
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4"><strong>Description</strong></dt>
                                <dd class="col-sm-8">{{ $mouvement->description ?? '-' }}</dd>

                                <dt class="col-sm-4"><strong>Montant Total</strong></dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-success" style="font-size: 1.1em;">
                                        {{ number_format($mouvement->montant_total, 0) }} Ar
                                    </span>
                                </dd>

                                <dt class="col-sm-4"><strong>Créé le</strong></dt>
                                <dd class="col-sm-8">{{ $mouvement->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles du mouvement -->
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Articles du Mouvement</h5>
                </div>
                <div class="card-body">
                    @if($mouvement->mvtStockFille && $mouvement->mvtStockFille->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">Photo</th>
                                        <th>Article</th>
                                        <th class="text-center">Unité</th>
                                        <th class="text-center">Entrée</th>
                                        <th class="text-center">Sortie</th>
                                        <th class="text-end">Prix Unitaire</th>
                                        <th class="text-end">Montant</th>
                                        <th>Date Expiration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mouvement->mvtStockFille as $fille)
                                    <tr>
                                        <td class="text-center">
                                            @if($fille->article->photo)
                                                <img src="{{ asset('storage/' . $fille->article->photo) }}" 
                                                     class="rounded shadow-sm" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $fille->article?->id_article ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $fille->article?->designation ?? '-' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $fille->article->unite->libelle ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($fille->entree > 0)
                                                <span class="badge bg-success">+{{ number_format($fille->entree, 0) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($fille->sortie > 0)
                                                <span class="badge bg-danger">-{{ number_format($fille->sortie, 0) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ number_format($fille->prix_unitaire, 0) }} Ar</td>
                                        <td class="text-end">
                                            <strong>{{ number_format($fille->getMontantAttribute(), 0) }} Ar</strong>
                                        </td>
                                        <td>
                                            @if($fille->date_expiration)
                                                {{ $fille->date_expiration->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="4" class="text-end">Total Mouvement:</th>
                                        <th class="text-end">
                                            <strong>{{ number_format($mouvement->montant_total, 0) }} Ar</strong>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Aucun article dans ce mouvement.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
                @if($mouvement->mvtStockFille && $mouvement->mvtStockFille->count() > 0)
                    <a href="{{ route('mvt-stock.exportPdf', $mouvement->id_mvt_stock) }}" class="btn btn-warning" target="_blank">
                        <i class="bi bi-file-pdf"></i> Exporter PDF
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
