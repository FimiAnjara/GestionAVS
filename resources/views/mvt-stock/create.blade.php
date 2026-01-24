@extends('layouts.app')

@section('title', 'Saisir un Mouvement de Stock')

@section('header-buttons')
    <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
    @if ($bonReception)
        <a href="{{ route('bon-reception.show', $bonReception->id_bonReception) }}" class="btn btn-info">
            <i class="bi bi-file-earmark-check me-2"></i>Retour au Bon {{ $bonReception->id_bonReception }}
        </a>
    @endif
@endsection

@section('content')
    @if ($bonReception)
        <div class="alert alert-info border-0 mb-4">
            <i class="bi bi-info-circle me-2"></i>
            Création d'un mouvement de stock depuis le bon de réception <strong>{{ $bonReception->id_bonReception }}</strong>
        </div>
    @endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 py-3">
        <h5 class="mb-0">
            <i class="bi bi-arrow-left-right me-2" style="color: #0056b3;"></i>
            Saisir un Mouvement de Stock
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('mvt-stock.store') }}" method="POST" id="mvtStockForm">
            @csrf

            <!-- Informations du mouvement -->
            <div class="row mb-4">
                <div class="col-lg-4">
                    <label for="id_mvt_stock" class="form-label">
                        <i class="bi bi-key me-2"></i>
                        ID Mouvement
                    </label>
                    <input type="text" class="form-control @error('id_mvt_stock') is-invalid @enderror" 
                           id="id_mvt_stock" name="id_mvt_stock" 
                           value="{{ old('id_mvt_stock', 'MVT_' . uniqid()) }}" readonly>
                    @error('id_mvt_stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-4">
                    <label for="date_" class="form-label">
                        <i class="bi bi-calendar me-2"></i>
                        Date
                    </label>
                    <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                           id="date_" name="date_" 
                           value="{{ old('date_', date('Y-m-d')) }}" required>
                    @error('date_')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-lg-4">
                    <label for="id_magasin" class="form-label">
                        <i class="bi bi-shop me-2"></i>
                        Magasin <span id="magasin-detail-badge" class="badge bg-light text-dark ms-2" style="display:none; font-weight: normal;"></span>
                    </label>
                    <select class="form-select searchable-select @error('id_magasin') is-invalid @enderror" 
                            id="id_magasin" name="id_magasin">
                        <option value="">-- Sélectionner un magasin --</option>
                        @foreach($magasins as $magasin)
                            <option value="{{ $magasin->id_magasin }}" 
                                    data-entite="{{ $magasin->site?->entite?->nom ?? 'N/A' }}"
                                    data-site="{{ $magasin->site?->localisation ?? 'N/A' }}"
                                    @selected(old('id_magasin') == $magasin->id_magasin || ($prefilledMagasin && $prefilledMagasin == $magasin->id_magasin))>
                                {{ $magasin->id_magasin }} - {{ $magasin->nom ?? $magasin->designation }} 
                                [{{ $magasin->site?->entite?->nom ?? 'N/A' }} | {{ $magasin->site?->localisation ?? 'N/A' }}]
                            </option>
                        @endforeach
                    </select>
                    @error('id_magasin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="magasin-info" class="mt-2 small text-muted"></div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="form-label">
                    <i class="bi bi-file-text me-2"></i>
                    Description (optionnel)
                </label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="2" 
                          placeholder="Notes sur ce mouvement">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Articles -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
                    Articles du Mouvement
                </label>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="articlesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Article</th>
                                <th class="text-center" width="12%">Entrée</th>
                                <th class="text-center" width="12%">Sortie</th>
                                <th class="text-center" width="12%">Prix Unit.</th>
                                <th class="text-center" width="12%">Montant (Ar)</th>
                                <th class="text-center" width="15%">Date Exp.</th>
                                <th class="text-center" width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="articlesContainer">
                            <tr class="article-row">
                                <td>
                                    <select class="form-select form-select-sm searchable-select @error('articles.0.id_article') is-invalid @enderror" 
                                            name="articles[0][id_article]" required>
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($articles as $article)
                                            <option value="{{ $article->id_article }}">
                                                {{ $article->id_article }} - {{ $article->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm entree-input @error('articles.0.entree') is-invalid @enderror" 
                                           name="articles[0][entree]" min="0" step="0.01" value="0" data-index="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm sortie-input @error('articles.0.sortie') is-invalid @enderror" 
                                           name="articles[0][sortie]" min="0" step="0.01" value="0" data-index="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm prix-input @error('articles.0.prix_unitaire') is-invalid @enderror" 
                                           name="articles[0][prix_unitaire]" min="0" step="0.01" value="0" data-index="0">
                                </td>
                                <td class="text-end">
                                    <span class="montant-display">0</span>
                                </td>
                                <td>
                                    <input type="date" class="form-control form-control-sm @error('articles.0.date_expiration') is-invalid @enderror" 
                                           name="articles[0][date_expiration]">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger btn-remove-article" style="display: none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total Mouvement:</th>
                                <th class="text-end"><span id="totalMontant" style="font-size: 1.1em; color: #0056b3;">0</span> Ar</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @error('articles')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
                <button type="button" class="btn btn-secondary btn-sm mt-3" id="btnAddArticle">
                    <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                </button>
            </div>

            <!-- Montant total (caché) -->
            <input type="hidden" id="montant_total" name="montant_total" value="0">

            <!-- Boutons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>
                    Enregistrer le Mouvement
                </button>
                <a href="{{ route('mvt-stock.list') }}" class="btn btn-secondary btn-lg">
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialiser Select2 pour les sélects existants
    initSelect2();
    
    let articleCount = 1;
    
    // Pré-remplir les articles depuis le bon de réception
    const prefilledArticles = @json($prefilledArticles ?? []);
    if (prefilledArticles && prefilledArticles.length > 0) {
        // Vider le conteneur initial
        $('#articlesContainer').empty();
        
        prefilledArticles.forEach((article, index) => {
            addArticleRow(article, index);
        });
        articleCount = prefilledArticles.length;
    }

    // Ajouter un article
    $('#btnAddArticle').on('click', function() {
        addArticleRow({}, articleCount);
        articleCount++;
        updateRemoveButtons();
    });

    // Supprimer un article
    $(document).on('click', '.btn-remove-article', function(e) {
        e.preventDefault();
        $(this).closest('.article-row').remove();
        updateRemoveButtons();
        calculateTotal();
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

    function attachCalculationListeners() {
        // Ajouter les écouteurs pour les inputs de calcul
        $(document).off('input change', '.entree-input, .sortie-input, .prix-input');
        $(document).on('input change', '.entree-input, .sortie-input, .prix-input', function() {
            calculateTotal();
        });
    }

    function calculateTotal() {
        let total = 0;
        
        document.querySelectorAll('.article-row').forEach((row) => {
            const entree = parseFloat(row.querySelector('.entree-input').value) || 0;
            const prix = parseFloat(row.querySelector('.prix-input').value) || 0;
            const montant = entree * prix;
            
            // Afficher le montant formaté
            row.querySelector('.montant-display').textContent = montant.toLocaleString('fr-FR', { 
                minimumFractionDigits: 0, 
                maximumFractionDigits: 0 
            });
            
            total += montant;
        });
        
        // Mettre à jour le total
        document.getElementById('totalMontant').textContent = total.toLocaleString('fr-FR', { 
            minimumFractionDigits: 0, 
            maximumFractionDigits: 0 
        });
        document.getElementById('montant_total').value = Math.round(total);
    }
    
    // Fonction pour ajouter une ligne d'article (réutilisable pour pré-remplissage)
    function addArticleRow(articleData = {}, index = articleCount) {
        const tbody = document.getElementById('articlesContainer');
        const html = `
            <tr class="article-row">
                <td>
                    <select class="form-select searchable-select" name="articles[${index}][id_article]" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id_article }}" ${articleData.id_article === '{{ $article->id_article }}' ? 'selected' : ''}>
                                {{ $article->id_article }} - {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" class="form-control entree-input" name="articles[${index}][entree]" min="0" step="0.01" value="${articleData.quantite || 0}"></td>
                <td><input type="number" class="form-control sortie-input" name="articles[${index}][sortie]" min="0" step="0.01" value="0"></td>
                <td><input type="number" class="form-control prix-input" name="articles[${index}][prix_unitaire]" min="0" step="0.01" value="${articleData.prix_unitaire || 0}"></td>
                <td class="text-center"><span class="montant-display">0</span></td>
                <td><input type="date" class="form-control" name="articles[${index}][date_expiration]"></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-remove-article" style="display:none;"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
        
        tbody.insertAdjacentHTML('beforeend', html);
        
        // Initialiser Select2 pour le nouveau select
        $('select[name="articles[' + index + '][id_article]"]').select2({
            language: 'fr',
            placeholder: '-- Sélectionner --',
            allowClear: true,
            width: '100%'
        });
        
        // Ajouter les écouteurs pour les nouveaux inputs
        attachCalculationListeners();
        calculateTotal();
    }

    // Attacher les écouteurs pour la première ligne
    attachCalculationListeners();
    updateRemoveButtons();

    // Gestion de l'affichage des détails du magasin
    $('#id_magasin').on('change', function() {
        const selected = $(this).find(':selected');
        const entite = selected.data('entite');
        const site = selected.data('site');
        const badge = $('#magasin-detail-badge');
        const infoDiv = $('#magasin-info');

        if ($(this).val()) {
            badge.text(`${entite}`).show();
            infoDiv.html(`<i class="bi bi-geo-alt me-1"></i> Site: <strong>${site}</strong> | <i class="bi bi-building me-1"></i> Entité: <strong>${entite}</strong>`).fadeIn();
        } else {
            badge.hide();
            infoDiv.hide().empty();
        }
    });

    // Déclencher au chargement si déjà rempli
    if ($('#id_magasin').val()) {
        $('#id_magasin').trigger('change');
    }
});
</script>
@endsection
