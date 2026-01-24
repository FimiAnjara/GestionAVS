@extends('layouts.app')

@section('title', 'Détails de la Facture')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                        <div class="col-sm-7"><span class="badge bg-primary">{{ $facture->id_factureFournisseur }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Date:</strong></div>
                        <div class="col-sm-7"><small>{{ $facture->date_->format('d/m/Y') }}</small></div>
                    </div>
                    @if($facture->bonCommande)
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Bon Commande:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-info text-dark">{{ $facture->bonCommande->id_bonCommande }}</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>Fournisseur:</strong></div>
                        <div class="col-sm-7"><span class="badge bg-warning text-dark">{{ $facture->bonCommande->fournisseur->nom ?? 'N/A' }}</span></div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>État:</strong></div>
                        <div class="col-sm-7">
                            <span class="badge bg-{{ $facture->etat_badge }}">{{ $facture->etat_label }}</span>
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
                    <div class="row mb-3">
                        <div class="col-sm-6"><strong>Montant Total:</strong></div>
                        <div class="col-sm-6"><span class="badge bg-success fs-6">{{ number_format($facture->montant_total ?? 0, 0, ',', ' ') }} Ar</span></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6"><strong>Montant Payé:</strong></div>
                        <div class="col-sm-6"><span class="badge bg-primary fs-6">{{ number_format($facture->montant_paye ?? 0, 0, ',', ' ') }} Ar</span></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"><strong>Reste à Payer:</strong></div>
                        <div class="col-sm-6">
                            <span class="badge fs-6 {{ ($facture->reste_a_payer ?? 0) > 0 ? 'bg-danger' : 'bg-success' }}">
                                {{ number_format($facture->reste_a_payer ?? 0, 0, ',', ' ') }} Ar
                            </span>
                        </div>
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
                            <th>Article</th>
                            <th>Quantité</th>
                            <th>Prix Achat</th>
                            <th class="text-end">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($facture->factureFournisseurFille as $ligne)
                        <tr>
                            <td><strong>{{ $ligne->article->nom ?? 'N/A' }}</strong></td>
                            <td class="text-center">{{ $ligne->quantite }}</td>
                            <td class="text-end">{{ number_format($ligne->prix_achat, 0, ',', ' ') }} Ar</td>
                            <td class="text-end">
                                @php 
                                    $montant = $ligne->quantite * $ligne->prix_achat;
                                    $total += $montant;
                                @endphp
                                <strong>{{ number_format($montant, 0, ',', ' ') }} Ar</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3" class="text-end">TOTAL:</th>
                            <th class="text-end"><strong>{{ number_format($total, 0, ',', ' ') }} Ar</strong></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="bi bi-sliders me-2"></i>Gestion d'État
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">État actuel: <strong>{{ $facture->etat_label }}</strong></p>
                    
                    @if($facture->etat == 1)
                    <!-- De Créée → Validée Finance ou Annulée -->
                    <form action="{{ route('facture-fournisseur.changerEtat', $facture->id_factureFournisseur) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="etat" value="5">
                        <button type="submit" class="btn btn-info btn-sm w-100 mb-2">
                            <i class="bi bi-check-circle me-2"></i>Valider par Finance
                        </button>
                    </form>
                    @elseif($facture->etat == 5)
                    <!-- De Validée Finance → Validée DG ou Annulée -->
                    <form action="{{ route('facture-fournisseur.changerEtat', $facture->id_factureFournisseur) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="etat" value="11">
                        <button type="submit" class="btn btn-success btn-sm w-100 mb-2">
                            <i class="bi bi-check-circle me-2"></i>Valider par DG
                        </button>
                    </form>
                    @elseif($facture->etat == 11)
                    <!-- Facture Validée par DG → Enregistrer Paiement -->
                    <a href="{{ route('mvt-caisse.create', ['id_facture' => $facture->id_factureFournisseur, 'montant' => $facture->reste_a_payer]) }}" 
                       class="btn btn-success btn-sm w-100 mb-2">
                        <i class="bi bi-credit-card me-2"></i>Enregistrer Paiement
                    </a>
                    @endif

                    @if($facture->etat != 0 && $facture->etat != 11)
                    <!-- Annulation -->
                    <form action="{{ route('facture-fournisseur.changerEtat', $facture->id_factureFournisseur) }}" method="POST">
                        @csrf
                        <input type="hidden" name="etat" value="0">
                        <button type="submit" class="btn btn-warning btn-sm w-100" 
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette facture?')">
                            <i class="bi bi-x-circle me-2"></i>Annuler
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-file-pdf me-2"></i>Documents
                    </h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('facture-fournisseur.exportPdf', $facture->id_factureFournisseur) }}" 
                       class="btn btn-outline-secondary btn-sm w-100 mb-2">
                        <i class="bi bi-file-pdf me-2"></i>Exporter PDF
                    </a>
                    @if($facture->etat != 11)
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteConfirmModal"
                            data-bs-url="{{ route('facture-fournisseur.destroy', $facture->id_factureFournisseur) }}"
                            data-bs-item="la facture {{ $facture->id_factureFournisseur }}"
                            title="Supprimer">
                        <i class="bi bi-trash me-2"></i>Supprimer
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
