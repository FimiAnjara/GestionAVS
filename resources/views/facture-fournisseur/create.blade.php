@extends('layouts.app')

@section('title', 'Créer une Facture Fournisseur')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <div>
            <h2><i class="bi bi-plus-circle"></i> Créer une nouvelle Facture Fournisseur</h2>
            <p class="text-muted">Remplissez le formulaire ci-dessous ou sélectionnez un bon de commande</p>
        </div>
    </div>

    @if($bonCommande)
    <!-- Formulaire de Création avec Bon de Commande -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-form-check"></i> Informations de la Facture
                    </h5>
                </div>
                <div class="card-body">
                    <form id="factureForm" method="POST" action="{{ route('facture-fournisseur.store') }}">
                        @csrf
                        
                        <!-- Info Bon de Commande -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="bi bi-receipt"></i> Bon de Commande <span class="text-danger">*</span>
                            </label>
                            <div class="alert alert-info mb-0">
                                <strong>{{ $bonCommande->id_bonCommande }}</strong> - 
                                Fournisseur: <strong>{{ $bonCommande->fournisseur->nom ?? 'N/A' }}</strong>
                            </div>
                            <input type="hidden" name="id_bonCommande" value="{{ $bonCommande->id_bonCommande }}">
                        </div>

                        <!-- Date Facture -->
                        <div class="mb-3">
                            <label for="date_" class="form-label">
                                <i class="bi bi-calendar-event"></i> Date de Facture <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                                   id="date_" name="date_" 
                                   value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                            @error('date_')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Notes ou observations">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg me-2">
                                <i class="bi bi-check-circle"></i> Créer la Facture
                            </button>
                            <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Articles du Bon de Commande -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul"></i> Articles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
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
                                    <td><small>{{ $ligne->article->nom ?? 'N/A' }}</small></td>
                                    <td class="text-end"><small>{{ $ligne->quantite }}</small></td>
                                    <td class="text-end"><small>{{ number_format($ligne->prix_achat, 2) }}</small></td>
                                </tr>
                                @php $total += $ligne->quantite * $ligne->prix_achat; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">TOTAL:</th>
                                    <th class="text-end"><strong>{{ number_format($total, 2) }}</strong></th>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-check"></i> Sélectionner un Bon de Commande
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i> Veuillez sélectionner un bon de commande validé par DG (état "Validé")
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                @forelse(\App\Models\BonCommande::where('etat', 11)->where('id_factureFournisseur', null)->latest()->get() as $bc)
                                <tr>
                                    <td><strong>{{ $bc->id_bonCommande }}</strong></td>
                                    <td>{{ $bc->fournisseur->nom ?? 'N/A' }}</td>
                                    <td>{{ $bc->date_->format('d/m/Y') }}</td>
                                    <td><span class="badge bg-success">Validé par DG</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('facture-fournisseur.createFromBonCommande', $bc->id_bonCommande) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-check"></i> Utiliser
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox"></i> Aucun bon de commande disponible
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>

<script>
    document.getElementById('factureForm').addEventListener('submit', async function(e) {
        const bonCommande = document.querySelector('input[name="id_bonCommande"]');
        if (!bonCommande || !bonCommande.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un bon de commande');
            return false;
        }
    });
</script>
@endsection
