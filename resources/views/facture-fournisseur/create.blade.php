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
                <i class="bi bi-receipt me-2" style="color: #0056b3;"></i>
                Nouvelle Facture Fournisseur
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('facture-fournisseur.store') }}" method="POST" id="factureForm">
                @csrf

                <div class="row mb-4">
                    <div class="col-lg-4">
                        <label for="date_" class="form-label">
                            <i class="bi bi-calendar me-2"></i>
                            Date de Facture
                        </label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" id="date_"
                            name="date_" value="{{ old('date_', date('Y-m-d')) }}" required>
                        @error('date_')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label d-block text-muted small mb-1">Bon de Commande :</label>
                        <div class="bg-light p-2 rounded border">
                            <strong class="text-primary">{{ $bonCommande->id_bonCommande }}</strong><br>
                            <small class="text-muted">Proforma : {{ $bonCommande->id_proformaFournisseur }}</small>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label d-block text-muted small mb-1">Fournisseur :</label>
                        <div class="bg-light p-2 rounded border">
                            <strong>{{ $bonCommande->proformaFournisseur->fournisseur->nom }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <label for="id_magasin" class="form-label">
                            <i class="bi bi-shop me-2"></i>Magasin Destination
                        </label>
                        <input type="text" class="form-control bg-light" value="{{ $bonCommande->magasin->nom ?? 'N/A' }} - {{ $bonCommande->magasin->site?->localisation ?? 'N/A' }}" readonly>
                        <input type="hidden" name="id_magasin" value="{{ $bonCommande->id_magasin }}">
                    </div>
                    <div class="col-lg-6">
                        <label for="description" class="form-label">Description (optionnel)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="1" placeholder="Détails ou notes sur cette facture">{{ old('description', $bonCommande->description ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Articles -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
                        Articles Facturés
                    </label>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="articlesTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">Photo</th>
                                    <th>Article</th>
                                    <th class="text-center" width="8%">Unité</th>
                                    <th class="text-center" width="15%">Quantité</th>
                                    <th class="text-center" width="15%">Prix Unit. (Ar)</th>
                                    <th class="text-center" width="15%">Total (Ar)</th>
                                    <th class="text-center" width="8%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="articlesContainer">
                                @foreach($bonCommande->bonCommandeFille as $index => $item)
                                    <tr class="article-row">
                                        <td class="text-center article-photo-container">
                                            @if($item->article?->photo)
                                                <img src="{{ asset('storage/' . $item->article->photo) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm searchable-select article-select @error('articles.'.$index.'.id_article') is-invalid @enderror"
                                                name="articles[{{ $index }}][id_article]" required>
                                                <option value="">-- Sélectionner --</option>
                                                @php $articles = \App\Models\Article::with('unite')->get(); @endphp
                                                @foreach ($articles as $article)
                                                    <option value="{{ $article->id_article }}"
                                                        data-unite="{{ $article->unite?->libelle }}"
                                                        data-photo="{{ $article->photo ? asset('storage/' . $article->photo) : '' }}"
                                                        {{ old('articles.'.$index.'.id_article', $item->id_article) == $article->id_article ? 'selected' : '' }}>
                                                        {{ $article->id_article }} - {{ $article->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center article-unite text-muted small">{{ $item->article?->unite?->libelle ?? '-' }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm quantite-input @error('articles.'.$index.'.quantite') is-invalid @enderror"
                                                name="articles[{{ $index }}][quantite]" placeholder="0" min="0.01" step="0.01" 
                                                value="{{ old('articles.'.$index.'.quantite', $item->quantite) }}" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm prix-input @error('articles.'.$index.'.prix') is-invalid @enderror"
                                                name="articles[{{ $index }}][prix]" placeholder="0" min="0" step="0.01" 
                                                value="{{ old('articles.'.$index.'.prix', $item->prix_achat) }}" required>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            <span class="row-total">{{ number_format($item->quantite * $item->prix_achat, 0, ',', ' ') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-article" 
                                                style="display: {{ $bonCommande->bonCommandeFille->count() > 1 ? 'inline-block' : 'none' }};">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <td colspan="5" class="text-end">Montant Total :</td>
                                    <td class="text-end text-primary" id="grandTotalDisplay">
                                        {{ number_format($bonCommande->bonCommandeFille->sum(fn($i) => $i->quantite * $i->prix_achat), 0, ',', ' ') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
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
                        Enregistrer la Facture
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
    // Initialiser Select2
    initSelect2();
    
    let articleCount = {{ count($bonCommande->bonCommandeFille ?? []) }};

    // Ajouter un article
    $('#btnAddArticle').on('click', function() {
        const container = document.getElementById('articlesContainer');
        const articles = @json($articlesJS);
        
        const html = `
            <tr class="article-row">
                <td class="text-center article-photo-container">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                </td>
                <td>
                    <select class="form-select form-select-sm searchable-select article-select" name="articles[${articleCount}][id_article]" required>
                        <option value="">-- Sélectionner --</option>
                        ${articles.map(art => `
                            <option value="${art.id}" data-unite="${art.unite}" data-photo="${art.photo}">
                                ${art.id} - ${art.nom}
                            </option>
                        `).join('')}
                    </select>
                </td>
                <td class="text-center article-unite text-muted small">-</td>
                <td>
                    <input type="number" class="form-control form-control-sm quantite-input" name="articles[${articleCount}][quantite]" placeholder="0" min="0.01" step="0.01" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm prix-input" name="articles[${articleCount}][prix]" placeholder="0" min="0" step="0.01" required>
                </td>
                <td class="text-end fw-bold text-primary">
                    <span class="row-total">0</span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-remove-article">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        container.insertAdjacentHTML('beforeend', html);
        
        // Initialiser Select2 pour le nouveau select
        initSelect2($(container).find('.article-select:last'));
        
        articleCount++;
        updateRemoveButtons();
    });

    // Supprimer un article
    $(document).on('click', '.btn-remove-article', function(e) {
        e.preventDefault();
        $(this).closest('.article-row').remove();
        updateRemoveButtons();
        calculateGrandTotal();
    });

    // Changement Article
    $(document).on('change', '.article-select', function() {
        const selected = $(this).find(':selected');
        const row = $(this).closest('tr');
        row.find('.article-unite').text(selected.data('unite') || '-');
        
        const photo = selected.data('photo');
        if (photo) {
            row.find('.article-photo-container').html(`<img src="${photo}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">`);
        } else {
            row.find('.article-photo-container').html(`<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`);
        }
    });

    // Calculs
    $(document).on('input', '.quantite-input, .prix-input', function() {
        const row = $(this).closest('tr');
        const qte = parseFloat(row.find('.quantite-input').val()) || 0;
        const prix = parseFloat(row.find('.prix-input').val()) || 0;
        row.find('.row-total').text((qte * prix).toLocaleString('fr-FR'));
        calculateGrandTotal();
    });

    function calculateGrandTotal() {
        let total = 0;
        $('.article-row').each(function() {
            const qte = parseFloat($(this).find('.quantite-input').val()) || 0;
            const prix = parseFloat($(this).find('.prix-input').val()) || 0;
            total += qte * prix;
        });
        $('#grandTotalDisplay').text(total.toLocaleString('fr-FR'));
    }

    function updateRemoveButtons() {
        const rows = $('.article-row').length;
        $('.btn-remove-article').toggle(rows > 1);
    }

    function initSelect2(element = null) {
        const target = element || $('.searchable-select');
        target.select2({
            language: 'fr',
            width: '100%',
            placeholder: '-- Sélectionner --'
        });
    }

    calculateGrandTotal();
    updateRemoveButtons();
});
</script>
@endsection
