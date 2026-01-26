@extends('layouts.app')

@section('title', 'Détails de la Proforma Client')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('proforma-client.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Informations Générales -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark me-2"></i>Informations Générales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>ID Proforma:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-primary">{{ $proforma->id_proforma_client }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Date:</strong></div>
                        <div class="col-sm-7"><small>{{ $proforma->date_->format('d/m/Y') }}</small></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Client:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-info text-dark">{{ $proforma->client->nom }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>État:</strong></div>
                        <div class="col-sm-7">
                            @php
                                $badge = 'secondary';
                                $label = 'Inconnu';
                                if($proforma->etat == 1) { $badge = 'primary'; $label = 'Créée'; }
                                elseif($proforma->etat == 11) { $badge = 'success'; $label = 'Validée'; }
                                elseif($proforma->etat == 0) { $badge = 'danger'; $label = 'Annulée'; }
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                        </div>
                    </div>
                    @if($proforma->magasin)
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Magasin Source:</strong></div>
                        <div class="col-sm-7">
                            <span class="fw-bold">{{ $proforma->nom_magasin }}</span><br>
                            <small class="text-muted">{{ $proforma->site_magasin }}</small>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-5"><strong>Créée le:</strong></div>
                        <div class="col-sm-7"><small class="text-muted">{{ $proforma->created_at->format('d/m/Y H:i') }}</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-calculator me-2"></i>Résumé Financier
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalMontant = $proforma->proformaClientFille->sum(function($item) { 
                            return $item->quantite * $item->prix; 
                        });
                    @endphp
                    <div class="row mb-3">
                        <div class="col-sm-6"><strong>Nombre d'articles:</strong></div>
                        <div class="col-sm-6"><span class="badge bg-info fs-6">{{ $proforma->proformaClientFille->count() }}</span></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"><strong>Montant Total:</strong></div>
                        <div class="col-sm-6"><span class="badge bg-success fs-6">{{ number_format($totalMontant, 0, ',', ' ') }} Ar</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles de la Proforma -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-basket me-2"></i>Articles
            </h5>
        </div>
        <div class="card-body p-0">
            @if($proforma->proformaClientFille->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Article</th>
                                <th class="text-end">Quantité</th>
                                <th class="text-end">Prix Unitaire</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proforma->proformaClientFille as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->article->nom }}</strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $item->id_article }}</small>
                                    </td>
                                    <td class="text-end">{{ number_format($item->quantite, 2, ',', ' ') }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-warning">{{ number_format($item->prix, 0, ',', ' ') }} Ar</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($item->quantite * $item->prix, 0, ',', ' ') }} Ar</strong>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="table-light fw-bold">
                                <td colspan="2" class="text-end">TOTAL:</td>
                                <td></td>
                                <td class="text-end">
                                    <span class="badge bg-success fs-6">
                                        {{ number_format($totalMontant, 0, ',', ' ') }} Ar
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted">Aucun article dans cette proforma</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Description -->
    @if($proforma->description)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-0 py-3">
                <h6 class="mb-0">
                    <i class="bi bi-file-text me-2"></i>Description
                </h6>
            </div>
            <div class="card-body">
                {{ $proforma->description }}
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('proforma-client.exportPdf', $proforma->id_proforma_client) }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-file-pdf me-2"></i>Exporter PDF
                </a>
                @if($proforma->etat == 1)
                    <a href="{{ route('proforma-client.edit', $proforma->id_proforma_client) }}" class="btn btn-secondary">
                        <i class="bi bi-pencil me-2"></i>Modifier
                    </a>
                    <form action="{{ route('proforma-client.etat', $proforma->id_proforma_client) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="etat" value="11">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Valider la Proforma
                        </button>
                    </form>
                @elseif($proforma->etat == 11)
                    <a href="{{ route('bon-commande-client.create', ['proforma_id' => $proforma->id_proforma_client]) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-plus me-2"></i>Créer Bon de Commande
                    </a>
                @endif
                <button type="button" class="btn btn-danger" 
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteConfirmModal"
                    data-bs-url="{{ route('proforma-client.destroy', $proforma->id_proforma_client) }}"
                    data-bs-item="la proforma {{ $proforma->id_proforma_client }}"
                    title="Annuler">
                    <i class="bi bi-trash me-2"></i>Annuler
                </button>
            </div>
        </div>
    </div>
@endsection
