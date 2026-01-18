@extends('layouts.app')

@section('title', 'Créer une Facture Fournisseur')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

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

    @if($bonCommande)
    <!-- Formulaire de Création avec Bon de Commande -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark me-2" style="color: #0056b3;"></i>
                Nouvelle Facture Fournisseur
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('facture-fournisseur.store') }}" method="POST" id="factureForm">
                @csrf

                <!-- Info Bon de Commande -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Bon de Commande:</strong> {{ $bonCommande->id_bonCommande }}
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label for="date_" class="form-label">
                            <i class="bi bi-calendar me-2" style="color: #0d0d0e;"></i>
                            Date de Facture
                        </label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" id="date_"
                            name="date_" value="{{ old('date_', date('Y-m-d')) }}" required>
                        @error('date_')
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
                        rows="3" placeholder="Détails ou notes sur cette facture">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Articles -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
                        Articles
                    </label>
                    <div id="articlesContainer">
                        @php $index = 0; @endphp
                        @foreach($bonCommande->bonCommandeFille as $ligne)
                        <div class="row g-2 article-row mb-3">
                            <div class="col-lg-5">
                                <select class="form-select searchable-select @error('articles.'.$index.'.id_article') is-invalid @enderror"
                                    name="articles[{{ $index }}][id_article]" required>
                                    <option value="">-- Sélectionner un article --</option>
                                    @php $articles = \App\Models\Article::all(); @endphp
                                    @foreach ($articles as $article)
                                        <option value="{{ $article->id_article }}"
                                            {{ $ligne->id_article == $article->id_article ? 'selected' : '' }}>
                                            {{ $article->id_article }} - {{ $article->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <input type="number"
                                    class="form-control @error('articles.'.$index.'.quantite') is-invalid @enderror"
                                    name="articles[{{ $index }}][quantite]" placeholder="Quantité" min="1" step="0.01"
                                    value="{{ $ligne->quantite }}" required>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control @error('articles.'.$index.'.prix') is-invalid @enderror"
                                        name="articles[{{ $index }}][prix]" placeholder="Prix" min="0" step="0.01"
                                        value="{{ $ligne->prix_achat }}" required>
                                    <span class="input-group-text">Ar</span>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <button type="button" class="btn btn-danger btn-remove-article" {{ count($bonCommande->bonCommandeFille) == 1 ? 'style="display: none;"' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        @php $index++; @endphp
                        @endforeach
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
                        Créer la Facture
                    </button>
                    <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-lg me-2"></i>
                        Annuler
                    </a>
                </div>

                <input type="hidden" name="id_bonCommande" value="{{ $bonCommande->id_bonCommande }}">
            </form>
        </div>
    </div>

    @else
    <!-- Sélection d'un Bon de Commande -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>Sélectionner un Bon de Commande
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>Veuillez sélectionner un bon de commande validé par DG
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fournisseur</th>
                            <th>Date</th>
                            <th>État</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\BonCommande::where('etat', 11)->where('id_factureFournisseur', null)->with('proformaFournisseur.fournisseur')->latest()->get() as $bc)
                        <tr>
                            <td><strong>{{ $bc->id_bonCommande }}</strong></td>
                            <td>{{ $bc->proformaFournisseur?->fournisseur?->nom ?? 'N/A' }}</td>
                            <td>{{ $bc->date_->format('d/m/Y') }}</td>
                            <td><span class="badge bg-success">Validé par DG</span></td>
                            <td class="text-center">
                                <a href="{{ route('facture-fournisseur.createFromBonCommande', $bc->id_bonCommande) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-check me-2"></i>Utiliser
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucun bon de commande disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

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
    
    let articleCount = {{ count($bonCommande->bonCommandeFille ?? []) }};

    // Ajouter un article
    $('#btnAddArticle').on('click', function() {
        const container = document.getElementById('articlesContainer');
        const articles = @json(\App\Models\Article::all());
        
        const html = `
            <div class="row g-2 article-row mb-3">
                <div class="col-lg-5">
                    <select class="form-select searchable-select" name="articles[${articleCount}][id_article]" required>
                        <option value="">-- Sélectionner un article --</option>
                        ${articles.map(art => `
                            <option value="${art.id_article}">
                                ${art.id_article} - ${art.nom}
                            </option>
                        `).join('')}
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
