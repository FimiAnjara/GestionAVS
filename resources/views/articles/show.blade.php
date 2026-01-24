@extends('layouts.app')

@section('title', 'Détails de l\'Article')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted">Informations complètes</p>
        </div>
        <div>
            <a href="{{ route('articles.edit', $article->id_article) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('articles.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Détails -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box"></i> Informations Article
                    </h5>
                </div>
                <div class="card-body">
                    @if($article->photo)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $article->photo) }}" alt="Photo" style="max-height: 300px; max-width: 100%;">
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">ID Article</label>
                            <p class="fw-bold">{{ $article->id_article }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nom</label>
                            <p class="fw-bold">{{ $article->nom }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="text-muted small">Catégorie</label>
                            <p class="fw-bold">
                                <span class="badge" style="background-color: {{ \App\Helpers\BadgeHelper::getCategoryColor($article->categorie->libelle ?? '') }}; color: white;">
                                    {{ $article->categorie->libelle ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Unité</label>
                            <p class="fw-bold">
                                <span class="badge" style="background-color: {{ \App\Helpers\BadgeHelper::getUnitColor($article->unite->libelle ?? '') }}; color: white;">
                                    {{ $article->unite->libelle ?? '-' }}
                                </span>
                            </p>
                        </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Entité</label>
                            <p class="fw-bold">
                                @if($article->entite)
                                    <a href="{{ route('entite.show', $article->id_entite) }}" class="text-decoration-none">
                                        <span class="badge bg-secondary">
                                            {{ $article->entite->nom }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Évaluation Stock</label>
                            <p class="fw-bold">
                                <span class="badge bg-info text-dark">
                                    {{ $article->typeEvaluation->libelle ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Créé le</label>
                            <p>{{ $article->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Modifié le</label>
                            <p>{{ $article->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte d'actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-exclamation-circle"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-danger w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteConfirmModal"
                        data-bs-url="{{ route('articles.destroy', $article->id_article) }}"
                        data-bs-item="{{ $article->nom }}">
                        <i class="bi bi-trash"></i> Supprimer cet article
                    </button>
                    <p class="small text-muted mt-3">
                        Cette action est irréversible. Soyez prudent.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
