@extends('layouts.app')

@section('title', 'Détails du Mouvement de Caisse')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('mvt-caisse.edit', $mouvement->id_mvt_caisse) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Modifier
        </a>
        <a href="{{ route('mvt-caisse.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-arrow-left-right me-2"></i>Informations du Mouvement
            </h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>ID Mouvement:</strong>
                </div>
                <div class="col-sm-9">
                    <span class="badge bg-primary">{{ $mouvement->id_mvt_caisse }}</span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Caisse:</strong>
                </div>
                <div class="col-sm-9">
                    <a href="{{ route('caisse.show', $mouvement->caisse->id_caisse) }}" class="badge bg-success text-decoration-none">
                        {{ $mouvement->caisse->id_caisse }}
                    </a>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Date:</strong>
                </div>
                <div class="col-sm-9">
                    <small class="text-muted">{{ $mouvement->date_->format('d/m/Y') }}</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Origine:</strong>
                </div>
                <div class="col-sm-9">
                    <span class="badge bg-secondary">{{ $mouvement->origine }}</span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3">
                    <strong>Description:</strong>
                </div>
                <div class="col-sm-9">
                    <p class="mb-0">{{ $mouvement->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-danger mb-2">
                        <i class="bi bi-dash-circle me-2"></i>Débit (Sortie)
                    </h6>
                    <h4 class="text-danger mb-0">
                        {{ number_format($mouvement->debit, 2, ',', ' ') }} Ar
                    </h4>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h6 class="text-success mb-2">
                        <i class="bi bi-plus-circle me-2"></i>Crédit (Entrée)
                    </h6>
                    <h4 class="text-success mb-0">
                        {{ number_format($mouvement->credit, 2, ',', ' ') }} Ar
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>Métadonnées
            </h6>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-sm-3">
                    <strong>Créé le:</strong>
                </div>
                <div class="col-sm-9">
                    <small class="text-muted">{{ $mouvement->created_at->format('d/m/Y H:i:s') }}</small>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <strong>Modifié le:</strong>
                </div>
                <div class="col-sm-9">
                    <small class="text-muted">{{ $mouvement->updated_at->format('d/m/Y H:i:s') }}</small>
                </div>
            </div>
        </div>
    </div>
@endsection
