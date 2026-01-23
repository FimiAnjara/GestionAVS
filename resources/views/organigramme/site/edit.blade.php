@extends('layouts.app')

@section('title', 'Modifier le Site')

@section('header-buttons')
    <a href="{{ route('site.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour à la liste
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h6 class="mb-0">
                <i class="bi bi-pencil me-2"></i>Modifier le site : {{ $site->localisation }}
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('site.update', $site->id_site) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="localisation" class="form-label">Localisation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('localisation') is-invalid @enderror" 
                                id="localisation" name="localisation" value="{{ old('localisation', $site->localisation) }}" required maxlength="50">
                            @error('localisation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_entite" class="form-label">Entité <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_entite') is-invalid @enderror" id="id_entite" name="id_entite" required>
                                <option value="">Sélectionner une entité</option>
                                @foreach ($entites as $entite)
                                    <option value="{{ $entite->id_entite }}" {{ old('id_entite', $site->id_entite) == $entite->id_entite ? 'selected' : '' }}>
                                        {{ $entite->nom }} ({{ $entite->groupe->nom ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_entite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Mettre à jour
                    </button>
                    <a href="{{ route('site.list') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
