@extends('layouts.app')

@section('title', 'Nouvelle Entité')

@section('header-buttons')
    <a href="{{ route('entite.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour à la liste
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-plus-circle me-2"></i>Créer une nouvelle entité
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('entite.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                id="nom" name="nom" value="{{ old('nom') }}" required maxlength="50">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_groupe" class="form-label">Groupe <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_groupe') is-invalid @enderror" id="id_groupe" name="id_groupe" required>
                                <option value="">Sélectionner un groupe</option>
                                @foreach ($groupes as $groupe)
                                    <option value="{{ $groupe->id_groupe }}" {{ old('id_groupe') == $groupe->id_groupe ? 'selected' : '' }}>
                                        {{ $groupe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_groupe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" value="{{ old('description') }}" maxlength="50">
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="code_couleur" class="form-label">Couleur</label>
                            <input type="color" class="form-control form-control-color @error('code_couleur') is-invalid @enderror" 
                                id="code_couleur" name="code_couleur" value="{{ old('code_couleur', '#3498db') }}">
                            @error('code_couleur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formats: JPG, PNG, GIF</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('entite.list') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
