@extends('layouts.app')

@section('title', 'Créer un Magasin')

@section('header-buttons')
    <a href="{{ route('magasin.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Magasin
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('magasin.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom du Magasin</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                id="nom" name="nom" placeholder="Ex: Magasin Centre Ville"
                                value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="number" step="0.000001"
                                        class="form-control @error('latitude') is-invalid @enderror"
                                        id="latitude" name="latitude"
                                        placeholder="Ex: -18.8792"
                                        value="{{ old('latitude', '-18.8792') }}" required>
                                    @error('latitude')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Entre -90 et 90</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="number" step="0.000001"
                                        class="form-control @error('longitude') is-invalid @enderror"
                                        id="longitude" name="longitude"
                                        placeholder="Ex: 47.5079"
                                        value="{{ old('longitude', '47.5079') }}" required>
                                    @error('longitude')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Entre -180 et 180</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Créer le Magasin
                            </button>
                            <a href="{{ route('magasin.list') }}" class="btn btn-light">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Aide -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Aide
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small">
                        <strong>Latitude & Longitude :</strong> Utilisez les coordonnées GPS d'Antananarivo.
                    </p>
                    <ul class="small">
                        <li>Latitude : entre -18.85 et -18.95</li>
                        <li>Longitude : entre 47.50 et 47.55</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
