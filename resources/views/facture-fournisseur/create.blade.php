@extends('app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3"><i class="bi bi-receipt"></i> Créer une Facture Fournisseur</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    @if($bonCommande)
                    <form action="{{ route('facture-fournisseur.store') }}" method="POST">
                        @csrf
                        
                        <!-- Info Bon de Commande -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5>Bon de Commande Sélectionné</h5>
                                <div class="alert alert-info">
                                    <strong>{{ $bonCommande->id_bonCommande }}</strong> - 
                                    Fournisseur: {{ $bonCommande->fournisseur->nom ?? 'N/A' }}
                                </div>
                                <input type="hidden" name="id_bonCommande" value="{{ $bonCommande->id_bonCommande }}">
                            </div>
                        </div>

                        <!-- Détails Facture -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date Facture *</label>
                                <input type="date" name="date_" class="form-control @error('date_') is-invalid @enderror" 
                                       value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                                @error('date_') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description') }}</textarea>
                                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Articles du Bon de Commande -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5>Articles</h5>
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
                                            @foreach($bonCommande->bonCommandeFille as $ligne)
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

                        <!-- Boutons -->
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-primary float-end">
                                    <i class="bi bi-check-circle"></i> Créer la Facture
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <!-- Sélection d'un Bon de Commande -->
                    <div class="alert alert-info mb-4">
                        Veuillez sélectionner un bon de commande validé par DG (état 11)
                    </div>

                    <h5>Bons de Commande Disponibles</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Fournisseur</th>
                                    <th>Date</th>
                                    <th>État</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\BonCommande::where('etat', 11)->where('id_factureFournisseur', null)->latest()->get() as $bc)
                                <tr>
                                    <td><strong>{{ $bc->id_bonCommande }}</strong></td>
                                    <td>{{ $bc->fournisseur->nom ?? 'N/A' }}</td>
                                    <td>{{ $bc->date_->format('d/m/Y') }}</td>
                                    <td><span class="badge bg-success">{{ $bc->etat_label }}</span></td>
                                    <td>
                                        <a href="{{ route('facture-fournisseur.createFromBonCommande', $bc->id_bonCommande) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-check"></i> Utiliser
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Aucun bon de commande disponible
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
