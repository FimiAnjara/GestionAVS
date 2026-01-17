@extends('layouts.app')

@section('title', 'Créer une Caisse')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-plus-circle me-2" style="color: #0056b3;"></i>
                Nouvelle Caisse
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('caisse.store') }}" method="POST">
                @csrf

                {{-- Libellé --}}
                <div class="mb-4">
                    <label for="libelle" class="form-label">
                        <i class="bi bi-bookmark me-2" style="color: #0056b3;"></i>
                        Libellé de la Caisse
                    </label>
                    <input type="text" 
                           class="form-control @error('libelle') is-invalid @enderror" 
                           id="libelle" 
                           name="libelle" 
                           placeholder="Ex: Airtel Money, MVola, Caisse Principale"
                           value="{{ old('libelle') }}"
                           required>
                    @error('libelle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Montant --}}
                <div class="mb-4">
                    <label for="montant" class="form-label">
                        <i class="bi bi-cash-coin me-2 text-success"></i>
                        Montant Initial
                    </label>
                    <div class="input-group input-group-lg">
                        <input type="number" 
                               class="form-control @error('montant') is-invalid @enderror" 
                               id="montant" 
                               name="montant" 
                               placeholder="0.00" 
                               step="0.01" 
                               min="0" 
                               value="{{ old('montant', 0) }}" 
                               required>
                        <span class="input-group-text">Ar</span>
                    </div>
                    @error('montant')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Boutons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>
                        Créer la Caisse
                    </button>
                    <a href="{{ route('caisse.list') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-lg me-2"></i>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
