@extends('layouts.app')

@section('title', 'Enregistrer Paiement de Facture')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <div>
            <h2><i class="bi bi-credit-card"></i> Enregistrer Paiement de Facture</h2>
            <p class="text-muted">Saisissez les détails du paiement fournisseur</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <strong>Erreurs:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-form-check"></i> Détails du Paiement
                    </h5>
                </div>
                <div class="card-body">
                    <form id="paymentForm" action="{{ route('mvt-caisse.store') }}" method="POST">
                        @csrf

                        <!-- Infos Facture -->
                        <div class="mb-4">
                            <label class="form-label text-muted">Facture</label>
                            <div class="alert alert-info mb-0">
                                <strong>{{ $facture->id_factureFournisseur }}</strong> - 
                                Fournisseur: <strong>{{ $facture->bonCommande->fournisseur->nom ?? 'N/A' }}</strong>
                            </div>
                        </div>

                        <!-- Date & Caisse -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_" class="form-label">
                                    <i class="bi bi-calendar-event"></i> Date *
                                </label>
                                <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                                       id="date_" name="date_" 
                                       value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                                @error('date_')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="id_caisse" class="form-label">
                                    <i class="bi bi-safe"></i> Caisse *
                                </label>
                                <select class="form-select @error('id_caisse') is-invalid @enderror" 
                                        id="id_caisse" name="id_caisse" required>
                                    <option value="">-- Sélectionner une caisse --</option>
                                    @foreach($caisses as $caisse)
                                    <option value="{{ $caisse->id_caisse }}" @if(old('id_caisse') == $caisse->id_caisse) selected @endif>
                                        {{ $caisse->libelle ?? $caisse->id_caisse }} (Solde: {{ number_format($caisse->montant, 2) }} Ar)
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_caisse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Montant Paiement -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="debit" class="form-label">
                                    <i class="bi bi-cash-out"></i> Montant à Payer *
                                </label>
                                <input type="number" class="form-control @error('debit') is-invalid @enderror" 
                                       id="debit" name="debit" step="0.01" 
                                       value="{{ old('debit', $reste_a_payer) }}" min="0" max="{{ $reste_a_payer }}" required>
                                @error('debit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Reste à payer: {{ number_format($reste_a_payer, 2) }} Ar</small>
                            </div>
                            <div class="col-md-6">
                                <label for="credit" class="form-label">
                                    <i class="bi bi-cash-in"></i> Montant Reçu (Crédit)
                                </label>
                                <input type="number" class="form-control @error('credit') is-invalid @enderror" 
                                       id="credit" name="credit" step="0.01" 
                                       value="{{ old('credit', 0) }}" min="0">
                                @error('credit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Montant entrant dans la caisse</small>
                            </div>
                        </div>

                        <!-- Origine (Facture ID, caché) -->
                        <input type="hidden" name="id_factureFournisseur" value="{{ $facture->id_factureFournisseur }}">
                        <input type="hidden" name="origine" value="{{ $facture->id_factureFournisseur }}">

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', 'Paiement facture ' . $facture->id_factureFournisseur) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg me-2">
                                <i class="bi bi-check-circle"></i> Enregistrer Paiement
                            </button>
                            <a href="{{ route('facture-fournisseur.show', $facture->id_factureFournisseur) }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Résumé Facture -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt"></i> Résumé Facture
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label text-muted small">Montant Total</label>
                            <p class="fs-6"><strong>{{ number_format($total, 2) }}</strong> Ar</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label text-muted small">Déjà Payé</label>
                            <p class="fs-6"><strong>{{ number_format($facture->montant_paye, 2) }}</strong> Ar</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label text-muted small">Reste à Payer</label>
                            <p class="fs-6">
                                <strong class="text-danger">{{ number_format($reste_a_payer, 2) }}</strong> Ar
                            </p>
                        </div>
                    </div>
                    
                    @if($facture->est_payee)
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle"></i> Facture complètement payée
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
