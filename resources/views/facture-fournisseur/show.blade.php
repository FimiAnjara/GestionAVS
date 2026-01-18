@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="bi bi-receipt"></i> {{ $facture->id_factureFournisseur }}
                <span class="badge {{ $facture->etat_badge }}">{{ $facture->etat_label }}</span>
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Détails Facture -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Détails</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Date</label>
                            <p class="fs-5">{{ $facture->date_->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">État</label>
                            <p class="fs-5">
                                <span class="badge {{ $facture->etat_badge }}">{{ $facture->etat_label }}</span>
                            </p>
                        </div>
                    </div>
                    
                    @if($facture->description)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted">Description</label>
                            <p>{{ $facture->description }}</p>
                        </div>
                    </div>
                    @endif

                    @if($facture->bonCommande)
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Bon de Commande</label>
                            <p>
                                <a href="{{ route('bon-commande.show', $facture->bonCommande->id_bonCommande) }}">
                                    {{ $facture->bonCommande->id_bonCommande }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Fournisseur</label>
                            <p>{{ $facture->bonCommande->fournisseur->nom ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Articles -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Articles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th>Quantité</th>
                                    <th>Prix Achat</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($facture->factureFournisseurFille as $ligne)
                                <tr>
                                    <td>{{ $ligne->article->nom ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $ligne->quantite }}</td>
                                    <td class="text-end">{{ number_format($ligne->prix_achat, 2) }}</td>
                                    <td class="text-end">
                                        @php 
                                            $montant = $ligne->quantite * $ligne->prix_achat;
                                            $total += $montant;
                                        @endphp
                                        {{ number_format($montant, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">TOTAL:</th>
                                    <th class="text-end">{{ number_format($total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-md-4">
            <!-- État Actuel -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Gestion d'État</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">État actuel: <strong>{{ $facture->etat_label }}</strong></p>
                    
                    @if($facture->etat == 1)
                    <!-- De Créée → Validée Finance ou Annulée -->
                    <form action="{{ route('facture-fournisseur.changerEtat', $facture->id_factureFournisseur) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="etat" value="5">
                        <button type="submit" class="btn btn-info btn-sm w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Valider par Finance
                        </button>
                    </form>
                    @elseif($facture->etat == 5)
                    <!-- De Validée Finance → Validée DG ou Annulée -->
                    <form action="{{ route('facture-fournisseur.changerEtat', $facture->id_factureFournisseur) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="etat" value="11">
                        <button type="submit" class="btn btn-success btn-sm w-100 mb-2">
                            <i class="bi bi-check-circle"></i> Valider par DG
                        </button>
                    </form>
                    @elseif($facture->etat == 11)
                    <!-- Facture Validée par DG → Créer Mouvement de Caisse -->
                    <a href="{{ route('mvt-caisse.createFromFacture', $facture->id_factureFournisseur) }}" 
                       class="btn btn-success btn-sm w-100 mb-2">
                        <i class="bi bi-cash-flow"></i> Créer MvtCaisse
                    </a>
                    @endif

                    @if($facture->etat != 0 && $facture->etat != 11)
                    <!-- Annulation -->
                    <form action="{{ route('facture-fournisseur.changerEtat', $facture->id_factureFournisseur) }}" method="POST">
                        @csrf
                        <input type="hidden" name="etat" value="0">
                        <button type="submit" class="btn btn-warning btn-sm w-100" 
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette facture?')">
                            <i class="bi bi-x-circle"></i> Annuler
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Documents</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('facture-fournisseur.exportPdf', $facture->id_factureFournisseur) }}" 
                       class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-file-pdf"></i> Exporter PDF
                    </a>
                </div>
            </div>

            <!-- Danger Zone -->
            @if($facture->etat != 11)
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">Zone Danger</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer la facture <strong>{{ $facture->id_factureFournisseur }}</strong> ?
                Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('facture-fournisseur.destroy', $facture->id_factureFournisseur) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
