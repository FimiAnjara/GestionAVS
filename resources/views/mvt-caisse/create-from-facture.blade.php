@extends('layouts.app')

@section('title', 'Créer Mouvement Caisse')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3"><i class="bi bi-cash-flow"></i> Créer Mouvement Caisse</h1>
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Depuis Facture Fournisseur</h5>
                </div>
                <div class="card-body">
                    <!-- Info Facture -->
                    <div class="alert alert-info mb-4">
                        <strong>Facture:</strong> {{ $facture->id_factureFournisseur }} 
                        <br>
                        <strong>Montant Total:</strong> {{ number_format($total, 2) }} Ar
                    </div>

                    <form action="{{ route('mvt-caisse.store') }}" method="POST">
                        @csrf

                        <!-- Date -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date *</label>
                                <input type="date" name="date_" class="form-control @error('date_') is-invalid @enderror" 
                                       value="{{ old('date_', now()->format('Y-m-Y')) }}" required>
                                @error('date_') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Caisse *</label>
                                <select name="id_caisse" class="form-select @error('id_caisse') is-invalid @enderror" required>
                                    <option value="">-- Sélectionner une caisse --</option>
                                    @foreach($caisses as $caisse)
                                    <option value="{{ $caisse->id_caisse }}" @if(old('id_caisse') == $caisse->id_caisse) selected @endif>
                                        {{ $caisse->nom }} (Solde: {{ number_format($caisse->montant, 2) }} Ar)
                                    </option>
                                    @endforeach
                                </select>
                                @error('id_caisse') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Montants -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Débit (Ar)</label>
                                <input type="number" name="debit" class="form-control @error('debit') is-invalid @enderror" 
                                       step="0.01" value="{{ old('debit', $total) }}" min="0">
                                @error('debit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <small class="text-muted">Montant payé au fournisseur</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Crédit (Ar)</label>
                                <input type="number" name="credit" class="form-control @error('credit') is-invalid @enderror" 
                                       step="0.01" value="{{ old('credit', 0) }}" min="0">
                                @error('credit') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                <small class="text-muted">Montant reçu</small>
                            </div>
                        </div>

                        <!-- Origine (masqué, pré-rempli) -->
                        <input type="hidden" name="origine" value="{{ $facture->id_factureFournisseur }}">

                        <!-- Description -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description', 'Paiement facture ' . $facture->id_factureFournisseur) }}</textarea>
                                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('facture-fournisseur.show', $facture->id_factureFournisseur) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-success float-end">
                                    <i class="bi bi-check-circle"></i> Créer Mouvement
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
