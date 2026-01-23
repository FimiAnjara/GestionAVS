@extends('layouts.app')

@section('title', 'Détails - ' . $magasin->nom)

@section('header-buttons')
    <a href="{{ route('magasin.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
    <a href="{{ route('magasin.edit', $magasin->id_magasin) }}" class="btn btn-warning">
        <i class="bi bi-pencil me-2"></i>Modifier
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-shop me-2"></i>Informations du Magasin
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small">ID</label>
                        <p class="form-control-plaintext fw-bold text-primary">{{ $magasin->id_magasin }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Nom</label>
                        <p class="form-control-plaintext fw-bold">{{ $magasin->nom }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Latitude</label>
                                <p class="form-control-plaintext">{{ number_format($magasin->latitude, 6) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Longitude</label>
                                <p class="form-control-plaintext">{{ number_format($magasin->longitude, 6) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Créé le</label>
                        <p class="form-control-plaintext">{{ $magasin->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($magasin->updated_at !== $magasin->created_at)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Modifié le</label>
                            <p class="form-control-plaintext">{{ $magasin->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-geo-alt me-2"></i>Localisation GPS
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Coordonnées GPS pour les cartes numériques
                    </div>

                    <div class="mb-3">
                        <a href="https://maps.google.com/?q={{ $magasin->latitude }},{{ $magasin->longitude }}"
                            target="_blank" class="btn btn-primary w-100">
                            <i class="bi bi-geo-alt me-2"></i>Voir sur Google Maps
                        </a>
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('magasin.carte') }}?nom={{ urlencode($magasin->nom) }}"
                            class="btn btn-info w-100">
                            <i class="bi bi-map me-2"></i>Voir sur notre Carte
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="mb-0">
                        <i class="bi bi-gear me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('magasin.destroy', $magasin->id_magasin) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce magasin ?')">
                            <i class="bi bi-trash me-2"></i>Supprimer ce Magasin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
