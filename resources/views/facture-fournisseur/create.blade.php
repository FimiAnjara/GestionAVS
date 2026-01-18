@extends('layouts.app')

@section('title', 'Créer une Facture Fournisseur')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    @if($bonCommande)
    <!-- Formulaire de Création avec Bon de Commande -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark me-2"></i>Nouvelle Facture Fournisseur
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="factureForm" method="POST" action="{{ route('facture-fournisseur.store') }}">
                        @csrf
                        
                        <!-- Info Bon de Commande -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-receipt me-2"></i><strong>Bon de Commande</strong>
                            </label>
                            <div class="alert alert-info mb-0">
                                <strong>{{ $bonCommande->id_bonCommande }}</strong> - 
                                Fournisseur: <strong>{{ $bonCommande->proformaFournisseur?->fournisseur?->nom ?? 'N/A' }}</strong>
                            </div>
                            <input type="hidden" name="id_bonCommande" value="{{ $bonCommande->id_bonCommande }}">
                        </div>

                        <!-- Date Facture -->
                        <div class="mb-4">
                            <label for="date_" class="form-label">
                                <i class="bi bi-calendar-event me-2"></i>Date de Facture <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                                   id="date_" name="date_" 
                                   value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                            @error('date_')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text me-2"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Notes ou observations">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Créer la Facture
                            </button>
                            <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Articles du Bon de Commande -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>Articles
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th class="text-end">Qté</th>
                                    <th class="text-end">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($bonCommande->bonCommandeFille as $ligne)
                                <tr>
                                    <td><small><strong>{{ $ligne->article->nom ?? 'N/A' }}</strong></small></td>
                                    <td class="text-end"><small>{{ $ligne->quantite }}</small></td>
                                    <td class="text-end"><small>{{ number_format($ligne->prix_achat, 0, ',', ' ') }} Ar</small></td>
                                </tr>
                                @php $total += $ligne->quantite * $ligne->prix_achat; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">TOTAL:</th>
                                    <th class="text-end"><strong>{{ number_format($total, 0, ',', ' ') }} Ar</strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Sélection d'un Bon de Commande -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>Sélectionner un Bon de Commande
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>Veuillez sélectionner un bon de commande validé par DG
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fournisseur</th>
                            <th>Date</th>
                            <th>État</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\BonCommande::where('etat', 11)->where('id_factureFournisseur', null)->with('proformaFournisseur.fournisseur')->latest()->get() as $bc)
                        <tr>
                            <td><strong>{{ $bc->id_bonCommande }}</strong></td>
                            <td>{{ $bc->proformaFournisseur?->fournisseur?->nom ?? 'N/A' }}</td>
                            <td>{{ $bc->date_->format('d/m/Y') }}</td>
                            <td><span class="badge bg-success">Validé par DG</span></td>
                            <td class="text-center">
                                <a href="{{ route('facture-fournisseur.createFromBonCommande', $bc->id_bonCommande) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-check me-2"></i>Utiliser
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucun bon de commande disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

@endsection
