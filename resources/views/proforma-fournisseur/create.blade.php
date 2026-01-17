@extends('layouts.app')

@section('title', 'Saisir une Proforma Fournisseur')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #dee2e6;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-top: 5px;
            color: #212529;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-plus me-2" style="color: #0056b3;"></i>
                Nouvelle Proforma d'Achat
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('proforma-fournisseur.store') }}" method="POST" id="proformaForm">
                @csrf

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label for="date_" class="form-label">
                            <i class="bi bi-calendar me-2" style="color: #0d0d0e;"></i>
                            Date
                        </label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" id="date_"
                            name="date_" value="{{ old('date_', date('Y-m-d')) }}" required>
                        @error('date_')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="id_fournisseur" class="form-label">
                            <i class="bi bi-briefcase me-2" style="color: #0056b3;"></i>
                            Fournisseur *
                        </label>
                        <select class="form-select searchable-select @error('id_fournisseur') is-invalid @enderror"
                            id="id_fournisseur" name="id_fournisseur" required>
                            <option value="">-- Sélectionner un fournisseur --</option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id_fournisseur }}"
                                    {{ old('id_fournisseur') == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                    {{ $fournisseur->id_fournisseur }} - {{ $fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_fournisseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">
                        <i class="bi bi-file-text me-2" style="color: #0056b3;"></i>
                        Description (optionnel)
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="3" placeholder="Détails ou notes sur cette proforma">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Articles -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
                        Articles à Acheter
                    </label>
                    <div id="articlesContainer">
                        <div class="row g-2 article-row mb-3">
                            <div class="col-lg-5">
                                <select
                                    class="form-select searchable-select @error('articles.0.id_article') is-invalid @enderror"
                                    name="articles[0][id_article]" required>
                                    <option value="">-- Sélectionner un article --</option>
                                    @foreach ($articles as $article)
                                        <option value="{{ $article->id_article }}">{{ $article->id_article }} -
                                            {{ $article->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <input type="number"
                                    class="form-control @error('articles.0.quantite') is-invalid @enderror"
                                    name="articles[0][quantite]" placeholder="Quantité" min="1" step="0.01"
                                    required>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('articles.0.prix') is-invalid @enderror"
                                        name="articles[0][prix]" placeholder="Prix" min="0" step="0.01" required>
                                    <span class="input-group-text">Ar</span>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <button type="button" class="btn btn-danger btn-remove-article" style="display: none;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('articles')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="btnAddArticle">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                    </button>
                </div>

                <!-- Boutons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>
                        Créer la Proforma
                    </button>
                    <a href="{{ route('proforma-fournisseur.list') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-lg me-2"></i>
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5.min.css" rel="stylesheet" />

<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #dee2e6;
        padding-top: 2px;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #212529;
    }
    
    .select2-dropdown {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialiser Select2 pour les sélects existants
    initSelect2();
    
    let articleCount = 1;

    // Ajouter un article
    $('#btnAddArticle').on('click', function() {
        const container = document.getElementById('articlesContainer');
        const html = `
            <div class="row g-2 article-row mb-3">
                <div class="col-lg-5">
                    <select class="form-select searchable-select" name="articles[${articleCount}][id_article]" required>
                        <option value="">-- Sélectionner un article --</option>
                        @foreach ($articles as $article)
                            <option value="{{ $article->id_article }}">
                                {{ $article->id_article }} - {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3">
                    <input type="number" class="form-control" name="articles[${articleCount}][quantite]" 
                           placeholder="Quantité" min="1" step="0.01" required>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="number" class="form-control" name="articles[${articleCount}][prix]" 
                               placeholder="Prix" min="0" step="0.01" required>
                        <span class="input-group-text">Ar</span>
                    </div>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger btn-remove-article" style="width: 100%;">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        articleCount++;
        
        // Initialiser Select2 pour le nouveau select
        $(container).find('.searchable-select:last').select2({
            language: 'fr',
            placeholder: '-- Sélectionner --',
            allowClear: true,
            width: '100%'
        });
        
        updateRemoveButtons();
    });

    // Supprimer un article
    $(document).on('click', '.btn-remove-article', function(e) {
        e.preventDefault();
        $(this).closest('.article-row').remove();
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.article-row');
        document.querySelectorAll('.btn-remove-article').forEach((btn, index) => {
            btn.style.display = rows.length > 1 ? 'block' : 'none';
        });
    }

    function initSelect2() {
        $('.searchable-select').select2({
            language: 'fr',
            placeholder: '-- Sélectionner --',
            allowClear: true,
            width: '100%'
        });
    }

    updateRemoveButtons();
});
</script>
@endsection
