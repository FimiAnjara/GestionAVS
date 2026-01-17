@extends('layouts.app')

@section('title', 'Modifier un Mouvement de Caisse')

@section('content')
    <form action="{{ route('mvt-caisse.update', $mouvement->id_mvt_caisse) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="id_mvt_caisse" class="form-label">
                        <i class="bi bi-key me-2 text-primary"></i>Identifiant du mouvement
                    </label>
                    <input type="text" class="form-control" id="id_mvt_caisse" value="{{ $mouvement->id_mvt_caisse }}" disabled>
                    <small class="text-muted">Cet identifiant ne peut pas être modifié</small>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="id_caisse" class="form-label">
                        <i class="bi bi-safe2 me-2 text-primary"></i>Caisse
                    </label>
                    <select class="form-select @error('id_caisse') is-invalid @enderror" id="id_caisse" name="id_caisse" required>
                        @foreach($caisses as $caisse)
                            <option value="{{ $caisse->id_caisse }}" {{ old('id_caisse', $mouvement->id_caisse) == $caisse->id_caisse ? 'selected' : '' }}>
                                {{ $caisse->id_caisse }} (Solde: {{ number_format($caisse->montant, 2, ',', ' ') }} Ar)
                            </option>
                        @endforeach
                    </select>
                    @error('id_caisse')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="date_" class="form-label">
                        <i class="bi bi-calendar me-2 text-primary"></i>Date
                    </label>
                    <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                        id="date_" name="date_" value="{{ old('date_', $mouvement->date_->format('Y-m-d')) }}" required>
                    @error('date_')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="origine" class="form-label">
                        <i class="bi bi-tag me-2 text-primary"></i>Origine
                    </label>
                    <input type="text" class="form-control @error('origine') is-invalid @enderror" 
                        id="origine" name="origine" placeholder="Ex: Vente, Achat, etc." value="{{ old('origine', $mouvement->origine) }}" required>
                    @error('origine')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="form-label">
                <i class="bi bi-file-text me-2 text-primary"></i>Description
            </label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                id="description" name="description" rows="4" placeholder="Détails du mouvement (optionnel)">{{ old('description', $mouvement->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <label for="debit" class="form-label">
                    <i class="bi bi-dash-circle me-2 text-danger"></i>Débit (Sortie)
                </label>
                <div class="input-group input-group-lg">
                    <input type="number" class="form-control @error('debit') is-invalid @enderror" 
                        id="debit" name="debit" placeholder="0.00" step="0.01" min="0" value="{{ old('debit', $mouvement->debit) }}">
                    <span class="input-group-text">Ar</span>
                </div>
                @error('debit')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-lg-6">
                <label for="credit" class="form-label">
                    <i class="bi bi-plus-circle me-2 text-success"></i>Crédit (Entrée)
                </label>
                <div class="input-group input-group-lg">
                    <input type="number" class="form-control @error('credit') is-invalid @enderror" 
                        id="credit" name="credit" placeholder="0.00" step="0.01" min="0" value="{{ old('credit', $mouvement->credit) }}">
                    <span class="input-group-text">Ar</span>
                </div>
                @error('credit')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle me-2"></i>Mettre à jour
            </button>
            <a href="{{ route('mvt-caisse.show', $mouvement->id_mvt_caisse) }}" class="btn btn-secondary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>
    </form>
@endsection
