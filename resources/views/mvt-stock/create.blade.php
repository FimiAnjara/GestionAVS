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
                    <label for="id_type_mvt" class="form-label">
                        <i class="bi bi-tag me-2"></i>
                        Type de Mouvement
                    </label>
                    <select class="form-select @error('id_type_mvt') is-invalid @enderror" 
                            id="id_type_mvt" name="id_type_mvt" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($typeMvts as $type)
                            <option value="{{ $type->id_type_mvt }}" @selected(old('id_type_mvt', $prefilledTypeMvt) == $type->id_type_mvt)>
                                {{ $type->libelle }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_type_mvt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-lg-6">
                    <label for="id_magasin" class="form-label">
                        <i class="bi bi-shop me-2"></i>
                        Magasin <span id="magasin-detail-badge" class="badge bg-light text-dark ms-2" style="display:none; font-weight: normal;"></span>
                    </label>
                    <select class="form-select searchable-select @error('id_magasin') is-invalid @enderror" 
                            id="id_magasin" name="id_magasin" required>
                        <option value="">-- Sélectionner un magasin --</option>
                        @foreach($magasins as $magasin)
                            <option value="{{ $magasin->id_magasin }}" 
                                    data-id-entite="{{ $magasin->site?->id_entite }}"
                                    data-entite="{{ $magasin->site?->entite?->nom ?? 'N/A' }}"
                                    data-site="{{ $magasin->site?->localisation ?? 'N/A' }}"
                                    @selected(old('id_magasin') == $magasin->id_magasin || ($prefilledMagasin && $prefilledMagasin == $magasin->id_magasin))>
                                [{{ $magasin->site?->entite?->nom ?? 'N/A' }}] {{ $magasin->site?->localisation ?? 'N/A' }} - {{ $magasin->nom ?? $magasin->designation }}
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
                                <th width="5%">Photo</th>
                                <th>Article</th>
                                <th class="text-center" width="8%">Unité</th>
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
                                <td class="text-center article-photo-container">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm searchable-select article-select @error('articles.0.id_article') is-invalid @enderror" 
                                            name="articles[0][id_article]" required>
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($articles as $article)
                                            <option value="{{ $article->id_article }}" 
                                                    data-id-entite="{{ $article->id_entite }}"
                                                    data-unite="{{ $article->unite?->libelle }}"
                                                    data-perissable="{{ $article->categorie?->est_perissable ? '1' : '0' }}"
                                                    data-photo="{{ $article->photo ? asset('storage/' . $article->photo) : '' }}">
                                                {{ $article->id_article }} - {{ $article->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success article-unite">-</span>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm entree-input @error('articles.0.entree') is-invalid @enderror" 
                                           name="articles[0][entree]" min="0" step="any" value="0" data-index="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm sortie-input @error('articles.0.sortie') is-invalid @enderror" 
                                           name="articles[0][sortie]" min="0" step="any" value="0" data-index="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm prix-input @error('articles.0.prix_unitaire') is-invalid @enderror" 
                                           name="articles[0][prix_unitaire]" min="0" step="any" value="0" data-index="0">
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
                                <th colspan="6" class="text-end">Total Mouvement:</th>
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
            const sortie = parseFloat(row.querySelector('.sortie-input').value) || 0;
            const prix = parseFloat(row.querySelector('.prix-input').value) || 0;
            const montant = (entree + sortie) * prix;
            
            // Afficher le montant formaté
            row.querySelector('.montant-display').textContent = montant.toLocaleString('fr-FR', { 
                minimumFractionDigits: 0, 
                maximumFractionDigits: 2 
            });
            
            total += montant;
        });
        
        // Mettre à jour le total
        document.getElementById('totalMontant').textContent = total.toLocaleString('fr-FR', { 
            minimumFractionDigits: 0, 
            maximumFractionDigits: 2 
        });
        document.getElementById('montant_total').value = total.toFixed(2);
    }
    
    // Fonction pour ajouter une ligne d'article (réutilisable pour pré-remplissage)
    function addArticleRow(articleData = {}, index = articleCount) {
        const tbody = document.getElementById('articlesContainer');
        const photoUrl = articleData.photo ? `{{ asset('storage') }}/${articleData.photo}` : '';
        const photoHtml = photoUrl 
            ? `<img src="${photoUrl}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">`
            : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`;

        const html = `
            <tr class="article-row">
                <td class="text-center article-photo-container">
                    ${photoHtml}
                </td>
                <td>
                    <select class="form-select searchable-select article-select" name="articles[${index}][id_article]" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id_article }}" 
                                    data-id-entite="{{ $article->id_entite }}"
                                    data-unite="{{ $article->unite?->libelle }}"
                                    data-perissable="{{ $article->categorie?->est_perissable ? '1' : '0' }}"
                                    data-photo="{{ $article->photo ? asset('storage/' . $article->photo) : '' }}"
                                    ${articleData.id_article === '{{ $article->id_article }}' ? 'selected' : ''}>
                                {{ $article->id_article }} - {{ $article->nom }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="text-center">
                    <span class="badge bg-success article-unite">${articleData.unite || '-'}</span>
                </td>
                <td><input type="number" class="form-control entree-input" name="articles[${index}][entree]" min="0" step="any" value="${articleData.quantite || 0}"></td>
                <td><input type="number" class="form-control sortie-input" name="articles[${index}][sortie]" min="0" step="any" value="0"></td>
                <td><input type="number" class="form-control prix-input" name="articles[${index}][prix_unitaire]" min="0" step="any" value="${articleData.prix_unitaire || 0}"></td>
                <td class="text-center"><span class="montant-display">0</span></td>
                <td><input type="date" class="form-control" name="articles[${index}][date_expiration]" value="${articleData.date_expiration || ''}"></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm btn-remove-article" style="display:none;"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
        
        tbody.insertAdjacentHTML('beforeend', html);
        
        // Initialiser Select2 pour le nouveau select
        const newSelect = $('select[name="articles[' + index + '][id_article]"]');
        newSelect.select2({
            language: 'fr',
            placeholder: '-- Sélectionner --',
            allowClear: true,
            width: '100%'
        });

        // Appliquer le filtre si un magasin est sélectionné
        const selectedEntite = $('#id_magasin').find(':selected').data('id-entite');
        if (selectedEntite) {
            filterArticlesByEntite(newSelect, selectedEntite);
        }

        // Déclencher le changement pour mettre à jour photo/unité si pré-rempli
        if (articleData.id_article) {
            newSelect.trigger('change');
        }
        
        // Ajouter les écouteurs pour les nouveaux inputs
        attachCalculationListeners();
        calculateTotal();
    }

    // Attacher les écouteurs pour la première ligne
    attachCalculationListeners();
    updateRemoveButtons();

    // Gestion de l'affichage des détails du magasin et filtrage des articles
    $('#id_magasin').on('change', function() {
        const selected = $(this).find(':selected');
        const idEntite = selected.data('id-entite');
        const entite = selected.data('entite');
        const site = selected.data('site');
        const badge = $('#magasin-detail-badge');
        const infoDiv = $('#magasin-info');

        if ($(this).val()) {
            badge.text(`${entite}`).show();
            infoDiv.html(`<i class="bi bi-geo-alt me-1"></i> Site: <strong>${site}</strong> | <i class="bi bi-building me-1"></i> Entité: <strong>${entite}</strong>`).fadeIn();
            
            // Filtrer tous les selects d'articles existants
            $('.article-select').each(function() {
                filterArticlesByEntite($(this), idEntite);
            });
        } else {
            badge.hide();
            infoDiv.hide().empty();
            
            // Réinitialiser les filtres
            $('.article-select').each(function() {
                filterArticlesByEntite($(this), null);
            });
        }
    });

    function filterArticlesByEntite(selectElement, idEntite) {
        // Sauvegarder toutes les options si ce n'est pas déjà fait
        if (!selectElement.data('all-options')) {
            selectElement.data('all-options', selectElement.html());
        }

        const allOptionsHtml = $(selectElement.data('all-options'));
        let selectedValue = selectElement.val();
        let matchFound = false;

        // Filtrer les options et reconstruire le select
        selectElement.empty();
        
        allOptionsHtml.each(function() {
            const articleEntite = $(this).data('id-entite');
            const val = $(this).val();
            
            // On affiche si : pas d'entité sélectionnée, ou entité correspond, ou c'est l'option vide
            if (!idEntite || !articleEntite || articleEntite == idEntite || val === "") {
                selectElement.append($(this).clone());
                if (val === selectedValue) matchFound = true;
            }
        });

        // Si l'article précédemment sélectionné ne correspond plus, on vide le select
        if (selectedValue && !matchFound) {
            selectElement.val('').trigger('change.select2');
        } else if (selectedValue) {
            selectElement.val(selectedValue).trigger('change.select2');
        } else {
            selectElement.trigger('change.select2');
        }
    }

    // Mise à jour de la photo et de l'unité lors de la sélection d'un article
    $(document).on('change', '.article-select', function() {
        const selected = $(this).find(':selected');
        const photoUrl = selected.data('photo');
        const unit = selected.data('unite');
        const row = $(this).closest('tr');
        const container = row.find('.article-photo-container');
        const unitDisplay = row.find('.article-unite');
        
        if (photoUrl) {
            container.html(`<img src="${photoUrl}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">`);
        } else {
            container.html(`<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`);
        }

        unitDisplay.text(unit || '-');
        if (unit) {
            unitDisplay.removeClass('d-none');
        } else {
            unitDisplay.addClass('d-none');
        }

        // Date d'expiration par défaut si périssable
        const selectedOption = $(this).find('option:selected');
        const isPerissable = selectedOption.attr('data-perissable') === '1';
        const dateInput = row.find('input[type="date"]');
        
        if (isPerissable) {
            // Si le champ est vide, on met une date par défaut en 2027 (ex: 1er Janvier 2027)
            if (!dateInput.val()) {
                dateInput.val('2027-01-01');
            }
        }

        // Auto-remplir le prix unitaire pour les sorties
        const idTypeMvt = $('#id_type_mvt').val();
        const idMagasin = $('#id_magasin').val();
        const idArticle = $(this).val();
        const prixInput = row.find('.prix-input');

        // Vérifier si c'est un mouvement de sortie (ne contient pas "entrée" ou "réception")
        if (idTypeMvt && idMagasin && idArticle) {
            const typeMvtText = $('#id_type_mvt option:selected').text().toLowerCase();
            const isSortie = !typeMvtText.includes('entrée') && 
                           !typeMvtText.includes('entree') && 
                           !typeMvtText.includes('réception') &&
                           !typeMvtText.includes('reception');

            if (isSortie) {
                // Appel AJAX pour obtenir le prix actuel
                $.ajax({
                    url: '{{ route('mvt-stock.api.prix-actuel') }}',
                    method: 'GET',
                    data: {
                        id_article: idArticle,
                        id_magasin: idMagasin
                    },
                    success: function(response) {
                        if (response.prix && response.prix > 0) {
                            prixInput.val(response.prix);
                            // Recalculer le total
                            calculateTotal();
                        }
                    },
                    error: function() {
                        console.error('Erreur lors de la récupération du prix actuel');
                    }
                });
            }
        }
    });

    // Déclencher au chargement si déjà rempli
    if ($('#id_magasin').val()) {
        $('#id_magasin').trigger('change');
    }
});
</script>
@endsection
