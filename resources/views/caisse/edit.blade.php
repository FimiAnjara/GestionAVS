@extends('layouts.app')

@section('title', 'Modifier la Caisse')

@section('content')
    <form action="{{ route('caisse.update', $caisse->id_caisse) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="id_caisse" class="form-label">
                        <i class="bi bi-key me-2 text-primary"></i>Identifiant de la caisse
                    </label>
                    <input type="text" class="form-control" id="id_caisse" value="{{ $caisse->id_caisse }}" disabled>
                    <small class="text-muted">Cet identifiant ne peut pas être modifié</small>
                </div>

                <div class="mb-3">
                    <label for="libelle" class="form-label">
                        <i class="bi bi-bookmark me-2" style="color: #0056b3;"></i>Libellé
                    </label>
                    <input type="text" class="form-control @error('libelle') is-invalid @enderror" 
                        id="libelle" name="libelle" placeholder="Ex: Airtel Money, MVola"
                        value="{{ old('libelle', $caisse->libelle) }}" required>
                    @error('libelle')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="montant" class="form-label">
                        <i class="bi bi-cash-coin me-2 text-success"></i>Montant
                    </label>
                    <div class="input-group">
                        <input type="number" class="form-control @error('montant') is-invalid @enderror" 
                            id="montant" name="montant" placeholder="0.00" step="0.01" min="0" 
                            value="{{ old('montant', $caisse->montant) }}" required>
                        <span class="input-group-text">Ar</span>
                    </div>
                    @error('montant')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Mettre à jour
                    </button>
                    <a href="{{ route('caisse.show', $caisse->id_caisse) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Retour
                    </a>
                </div>
    </form>
@endsection
