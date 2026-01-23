@extends('layouts.app')

@section('title', 'Modifier le Groupe')

@section('header-buttons')
    <a href="{{ route('groupe.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour à la liste
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-pencil me-2"></i>Modifier le groupe : {{ $groupe->nom }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('groupe.update', $groupe->id_groupe) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du Groupe <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                id="nom" name="nom" value="{{ old('nom', $groupe->nom) }}" required maxlength="50">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Mettre à jour
                    </button>
                    <a href="{{ route('groupe.list') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
