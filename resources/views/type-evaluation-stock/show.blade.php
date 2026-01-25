@extends('layouts.app')

@section('title', 'Détails du Type d\'Évaluation')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted">Consultez les caractéristiques de cette méthode</p>
        </div>
        <div>
            <a href="{{ route('type-evaluation-stock.edit', $type->id_type_evaluation_stock) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('type-evaluation-stock.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informations Complètes</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Code Unique</label>
                            <p class="fw-bold"><span class="badge bg-info text-dark">{{ $type->id_type_evaluation_stock }}</span></p>
                        </div>
                        <div class="col-md-8">
                            <label class="text-muted small">Libellé</label>
                            <p class="fw-bold">{{ $type->libelle }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Description</label>
                        <div class="p-3 bg-light rounded border">
                            {{ $type->description ?: 'Aucune description disponible.' }}
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 text-muted small">
                            <p>Créé le : {{ $type->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-muted small text-md-end">
                            <p>Dernière modification : {{ $type->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">Zone de Danger</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        La suppression de ce type d'évaluation peut affecter les articles qui l'utilisent.
                    </p>
                    <button class="btn btn-danger w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteConfirmModal"
                            data-bs-url="{{ route('type-evaluation-stock.destroy', $type->id_type_evaluation_stock) }}"
                            data-bs-item="{{ $type->libelle }}">
                        <i class="bi bi-trash"></i> Supprimer ce type
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
