@extends('layouts.app')

@section('title', 'Créer un Bon de Commande')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('header-buttons')
    <a href="{{ route('bon-commande.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-arrow-up me-2" style="color: #0056b3;"></i>
                Nouveau Bon de Commande
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('bon-commande.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-lg-3">
                        <label for="date_" class="form-label">
                            <i class="bi bi-calendar me-2" style="color: #0056b3;"></i>
                            Date <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                            id="date_" name="date_" value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                        @error('date_') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-lg-5">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-proforma" data-bs-toggle="tab" data-bs-target="#panel-proforma" type="button" role="tab">Proforma</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-fournisseur" data-bs-toggle="tab" data-bs-target="#panel-fournisseur" type="button" role="tab">Fournisseur Direct</button>
                            </li>
                        </ul>
                        <div class="tab-content border border-top-0 p-2">
                            <div class="tab-pane fade show active" id="panel-proforma" role="tabpanel">
                                <label for="id_proformaFournisseur" class="form-label">
                                    <i class="bi bi-file-earmark-check me-2" style="color: #0056b3;"></i>
                                    Proforma Sourcée <span class="text-danger">*</span>
                                </label>
                                <select class="form-select searchable-select @error('id_proformaFournisseur') is-invalid @enderror" 
                                    id="id_proformaFournisseur" name="id_proformaFournisseur">
                                    <option value="">-- Sélectionner une proforma --</option>
                                    @foreach ($proformas as $proforma)
                                        <option value="{{ $proforma->id_proformaFournisseur }}"
                                            {{ old('id_proformaFournisseur', $proformaFournisseur?->id_proformaFournisseur) == $proforma->id_proformaFournisseur ? 'selected' : '' }}>
                                            {{ $proforma->id_proformaFournisseur }} - {{ $proforma->fournisseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_proformaFournisseur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="tab-pane fade" id="panel-fournisseur" role="tabpanel">
                                <label for="id_fournisseur_direct" class="form-label">
                                    <i class="bi bi-building me-2" style="color: #0056b3;"></i>
                                    Sélectionner un Fournisseur <span class="text-danger">*</span>
                                </label>
                                <select class="form-select searchable-select @error('id_fournisseur_direct') is-invalid @enderror" 
                                    id="id_fournisseur_direct" name="id_fournisseur_direct">
                                    <option value="">-- Sélectionner un fournisseur --</option>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id_fournisseur }}">
                                            {{ $fournisseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_fournisseur_direct') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 text-end d-flex align-items-end justify-content-end">
                        <div class="bg-light p-2 rounded border w-100">
                            <small class="text-muted d-block">Fournisseur :</small>
                            <strong id="fournisseur-display">{{ $proformaFournisseur->fournisseur->nom ?? '---' }}</strong>
                            <input type="hidden" name="id_fournisseur" id="id_fournisseur" value="{{ old('id_fournisseur', $proformaFournisseur?->id_fournisseur) }}">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="id_magasin" class="form-label">
                            <i class="bi bi-shop me-2" style="color: #0056b3;"></i>
                            Magasin Destination
                        </label>
                        <select class="form-select searchable-select @error('id_magasin') is-invalid @enderror"
                            id="id_magasin" name="id_magasin">
                            <option value="">-- Sélectionner un magasin --</option>
                            @foreach ($magasins as $magasin)
                                <option value="{{ $magasin->id_magasin }}" 
                                    {{ old('id_magasin', $idMagasinProforma) == $magasin->id_magasin ? 'selected' : '' }}>
                                    [{{ $magasin->site?->entite?->nom ?? 'N/A' }}] {{ $magasin->site?->localisation ?? 'N/A' }} - {{ $magasin->nom ?? $magasin->designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_magasin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="description" class="form-label">
                            <i class="bi bi-file-text me-2" style="color: #0056b3;"></i>
                            Description (optionnel)
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                            id="description" name="description" rows="1">{{ old('description', $descriptionProforma) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <!-- Articles -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
                        Articles Commandés <span class="text-danger">*</span>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
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
                            <tbody id="articles-container">
                                @if (isset($articlesProforma) && count($articlesProforma) > 0)
                                    @foreach ($articlesProforma as $index => $item)
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
                                                <input type="number" class="form-control form-control-sm quantite-input"
                                                    name="articles[{{ $index }}][quantite]" placeholder="0" min="0.01" step="0.01" 
                                                    value="{{ old('articles.'.$index.'.quantite', $item->quantite) }}" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm prix-input"
                                                    name="articles[{{ $index }}][prix]" placeholder="0" min="0" step="0.01" 
                                                    value="{{ old('articles.'.$index.'.prix', $item->prix_achat) }}" required>
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                <span class="row-total">{{ number_format($item->quantite * $item->prix_achat, 0, ',', ' ') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-article">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="article-row">
                                        <td class="text-center article-photo-container">
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm searchable-select article-select" name="articles[0][id_article]" required>
                                                <option value="">-- Sélectionner --</option>
                                                @foreach ($articles as $article)
                                                    <option value="{{ $article->id_article }}"
                                                        data-unite="{{ $article->unite?->libelle }}"
                                                        data-photo="{{ $article->photo ? asset('storage/' . $article->photo) : '' }}">
                                                        {{ $article->id_article }} - {{ $article->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center article-unite text-muted small">-</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm quantite-input" name="articles[0][quantite]" placeholder="0" min="0.01" step="0.01" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm prix-input" name="articles[0][prix]" placeholder="0" min="0" step="0.01" required>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            <span class="row-total">0</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-article" style="display: none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <td colspan="5" class="text-end">Montant Total :</td>
                                    <td class="text-end text-primary" id="grandTotalDisplay">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="btn-add-article">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                    </button>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer le Bon
                    </button>
                    <a href="{{ route('bon-commande.list') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Passer les articles disponibles au JavaScript
        const articlesData = @json($articlesJS);
        
        $(document).ready(function() {
            // Initialiser Select2
            initSelect2();
            
            // Gérer le changement de tab
            $('#tab-proforma, #tab-fournisseur').on('click', function() {
                // Réinitialiser les sélections
                $('#id_proformaFournisseur').val('').trigger('change');
                $('#id_fournisseur_direct').val('').trigger('change');
                $('#fournisseur-display').text('---');
                $('#id_fournisseur').val('');
            });
            
            // Écouter le changement du select proforma (et select2:select)
            $('#id_proformaFournisseur').on('change select2:select', function() {
                const proformaId = $(this).val();
                
                if (proformaId) {
                    // Récupérer les données de la proforma
                    $.ajax({
                        url: '{{ route("bon-commande.api.proforma", ":id") }}'.replace(':id', proformaId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Mettre à jour l'affichage du fournisseur
                            $('#fournisseur-display').text(data.fournisseur_nom);
                            $('#id_fournisseur').val(data.fournisseur_id);
                            
                            // Mettre à jour le magasin
                            if (data.id_magasin) {
                                $('#id_magasin').val(data.id_magasin).trigger('change');
                            }
                            
                            // Mettre à jour la description
                            if (data.description) {
                                $('#description').val(data.description);
                            }
                            
                            // Mettre à jour les articles
                            const container = document.getElementById('articles-container');
                            container.innerHTML = ''; 
                            
                            if (data.articles && data.articles.length > 0) {
                                data.articles.forEach((article, index) => {
                                    addArticleRow(article, index);
                                });
                                calculateGrandTotal();
                            }
                        }
                    });
                }
            });

            // Écouter le changement du select fournisseur direct
            $('#id_fournisseur_direct').on('change select2:select', function() {
                const fournisseurId = $(this).val();
                
                if (fournisseurId) {
                    $.ajax({
                        url: '{{ route("bon-commande.api.fournisseur", ":id") }}'.replace(':id', fournisseurId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#fournisseur-display').text(data.fournisseur_nom);
                            $('#id_fournisseur').val(data.fournisseur_id);
                            
                            // Vider les articles si le fournisseur change sans proforma
                            const container = document.getElementById('articles-container');
                            container.innerHTML = ''; 
                            addArticleRow(null, 0);
                            calculateGrandTotal();
                        }
                    });
                }
            });

            // Bouton Ajouter Article
            $('#btn-add-article').on('click', function() {
                const index = $('.article-row').length;
                addArticleRow(null, index);
            });

            // Supprimer Article
            $(document).on('click', '.btn-remove-article', function() {
                $(this).closest('tr').remove();
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

            function addArticleRow(data = null, index) {
                const container = document.getElementById('articles-container');
                let options = '<option value="">-- Sélectionner --</option>';
                articlesData.forEach(art => {
                    const selected = data && art.id === data.id_article ? 'selected' : '';
                    options += `<option value="${art.id}" ${selected} data-unite="${art.unite}" data-photo="${art.photo}">${art.id} - ${art.nom}</option>`;
                });

                const photoHtml = data && data.photo 
                    ? `<img src="${data.photo}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">`
                    : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`;

                const rowHtml = `
                    <tr class="article-row">
                        <td class="text-center article-photo-container">${photoHtml}</td>
                        <td>
                            <select class="form-select form-select-sm searchable-select article-select" name="articles[${index}][id_article]" required>
                                ${options}
                            </select>
                        </td>
                        <td class="text-center article-unite text-muted small">${data ? (data.unite || '-') : '-'}</td>
                        <td>
                            <input type="number" class="form-control form-control-sm quantite-input" name="articles[${index}][quantite]" value="${data ? data.quantite : ''}" placeholder="0" min="0.01" step="0.01" required>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm prix-input" name="articles[${index}][prix]" value="${data ? data.prix : ''}" placeholder="0" min="0" step="0.01" required>
                        </td>
                        <td class="text-end fw-bold text-primary">
                            <span class="row-total">${data ? (data.quantite * data.prix).toLocaleString('fr-FR') : '0'}</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger btn-remove-article">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                container.insertAdjacentHTML('beforeend', rowHtml);
                initSelect2($(container).find('.article-select:last'));
                updateRemoveButtons();
            }

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
