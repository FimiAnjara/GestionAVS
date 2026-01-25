@extends('layouts.app')

@section('title', 'Détails de la Facture Client')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('facture-client.list') }}" class="btn btn-secondary">
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
                        <div class="col-sm-5"><strong>ID Facture:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-primary">{{ $facture->id_facture_client }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Date:</strong></div>
                        <div class="col-sm-7"><small>{{ $facture->date_->format('d/m/Y') }}</small></div>
                    </div>
                    @if($facture->bonCommandeClient)
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Bon Commande:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-info text-dark">{{ $facture->id_bon_commande_client }}</span></div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Client:</strong></div>
                        <div class="col-sm-7"><strong>{{ $facture->client->nom }}</strong></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>État:</strong></div>
                        <div class="col-sm-7">
                            <span class="badge bg-{{ $facture->etat == 1 ? 'warning' : 'success' }}">
                                {{ $facture->etat == 1 ? 'Créée' : 'Validée' }}
                            </span>
                        </div>
                    </div>
                    @if($facture->description)
                    <div class="row">
                        <div class="col-sm-5"><strong>Description:</strong></div>
                        <div class="col-sm-7"><small>{{ $facture->description }}</small></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Résumé Financier -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cash me-2"></i>Résumé Financier
                    </h5>
                </div>
                <div class="card-body">
                    @php 
                        $total = $facture->factureClientFille->sum(function($f) { return $f->quantite * $f->prix; });
                    @endphp
                    <div class="row mb-3">
                        <div class="col-sm-6"><strong>Montant Total:</strong></div>
                        <div class="col-sm-6"><span class="badge bg-success fs-6">{{ number_format($total, 0, ',', ' ') }} Ar</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles de la Facture -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Articles
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">Photo</th>
                            <th>Article</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Prix Unitaire</th>
                            <th class="text-end">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facture->factureClientFille as $ligne)
                        <tr>
                            <td class="text-center">
                                @if($ligne->article->photo)
                                    <img src="{{ asset('storage/' . $ligne->article->photo) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>
                                @endif
                            </td>
                            <td><strong>{{ $ligne->article->nom }}</strong></td>
                            <td class="text-center">
                                {{ floatval($ligne->quantite) }}
                                <span class="badge bg-success ms-1">{{ $ligne->article->unite->libelle ?? '-' }}</span>
                            </td>
                            <td class="text-end">{{ number_format($ligne->prix, 0, ',', ' ') }} Ar</td>
                            <td class="text-end"><strong>{{ number_format($ligne->quantite * $ligne->prix, 0, ',', ' ') }} Ar</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">TOTAL:</th>
                            <th class="text-end"><strong>{{ number_format($total, 0, ',', ' ') }} Ar</strong></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex gap-2">
                <a href="{{ route('facture-client.exportPdf', $facture->id_facture_client) }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-file-pdf me-2"></i>Exporter PDF
                </a>
                
                @if($facture->etat == 1)
                <form action="{{ route('facture-client.etat', $facture->id_facture_client) }}" method="POST">
                    @csrf
                    <input type="hidden" name="etat" value="11">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Valider la Facture
                    </button>
                </form>
                @endif

                <button type="button" class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteConfirmModal"
                        data-bs-url="{{ route('facture-client.destroy', $facture->id_facture_client) }}"
                        data-bs-item="la facture client {{ $facture->id_facture_client }}"
                        title="Supprimer">
                    <i class="bi bi-trash me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
@endsection
