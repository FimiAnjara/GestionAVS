@extends('layouts.app')

@section('title', 'Modifier la Proforma Fournisseur')

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
                <i class="bi bi-file-earmark-check me-2" style="color: #0056b3;"></i>
                Modifier la Proforma d'Achat
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('proforma-fournisseur.update', $proforma->id_proformaFournisseur) }}" method="POST" id="proformaForm">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-lg-4">
                        <label for="date_" class="form-label">
                            <i class="bi bi-calendar me-2"></i>
                            Date
                        </label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" id="date_"
                            name="date_" value="{{ old('date_', $proforma->date_->format('Y-m-d')) }}" required>
                        @error('date_')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <label for="id_fournisseur" class="form-label">
                            <i class="bi bi-briefcase me-2" style="color: #0056b3;"></i>
                            Fournisseur *
                        </label>
                        <select class="form-select searchable-select @error('id_fournisseur') is-invalid @enderror"
                            id="id_fournisseur" name="id_fournisseur" required>
                            <option value="">-- Sélectionner un fournisseur --</option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id_fournisseur }}" 
                                    {{ old('id_fournisseur', $proforma->id_fournisseur) == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                    {{ $fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_fournisseur')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <label for="id_magasin" class="form-label">
                            <i class="bi bi-shop me-2" style="color: #0056b3;"></i>
                            Magasin Destination
                        </label>
                        <select class="form-select searchable-select @error('id_magasin') is-invalid @enderror"
                            id="id_magasin" name="id_magasin">
                            <option value="">-- Sélectionner un magasin --</option>
                            @foreach ($magasins as $magasin)
                                <option value="{{ $magasin->id_magasin }}" {{ old('id_magasin', $proforma->id_magasin) == $magasin->id_magasin ? 'selected' : '' }}>
                                    [{{ $magasin->site?->entite?->nom ?? 'N/A' }}] {{ $magasin->site?->localisation ?? 'N/A' }} - {{ $magasin->nom ?? $magasin->designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_magasin')
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
                        rows="2" placeholder="Détails ou notes sur cette proforma">{{ old('description', $proforma->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Articles -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
                        Articles de la Proforma
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
                                @foreach ($proforma->proformaFournisseurFille as $index => $item)
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
                                                style="display: {{ $proforma->proformaFournisseurFille->count() > 1 ? 'inline-block' : 'none' }};">
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
                                        {{ number_format($proforma->proformaFournisseurFille->sum(fn($i) => $i->quantite * $i->prix_achat), 0, ',', ' ') }}
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
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('proforma-fournisseur.show', $proforma->id_proformaFournisseur) }}" class="btn btn-secondary btn-lg">
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
    
    let articleCount = {{ $proforma->proformaFournisseurFille->count() }};

    // Ajouter un article
    $('#btnAddArticle').on('click', function() {
        const container = document.getElementById('articlesContainer');
        const articlesData = @json($articlesJS);

        let options = '<option value="">-- Sélectionner --</option>';
        articlesData.forEach(art => {
            options += `<option value="${art.id}" data-unite="${art.unite}" data-photo="${art.photo}">${art.id} - ${art.nom}</option>`;
        });

        const html = `
            <tr class="article-row">
                <td class="text-center article-photo-container">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-image text-muted"></i>
                    </div>
                </td>
                <td>
                    <select class="form-select form-select-sm searchable-select article-select" name="articles[${articleCount}][id_article]" required>
                        ${options}
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
        const newSelect = $(container).find('.article-select:last');
        newSelect.select2({
            language: 'fr',
            placeholder: '-- Sélectionner --',
            allowClear: true,
            width: '100%'
        });
        
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
    });

    // Calculs en temps réel
    $(document).on('input', '.quantite-input, .prix-input', function() {
        const row = $(this).closest('tr');
        const qty = parseFloat(row.find('.quantite-input').val()) || 0;
        const price = parseFloat(row.find('.prix-input').val()) || 0;
        const total = qty * price;
        
        row.find('.row-total').text(total.toLocaleString('fr-FR'));
        calculateGrandTotal();
    });

    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.article-row').each(function() {
            const qty = parseFloat($(this).find('.quantite-input').val()) || 0;
            const price = parseFloat($(this).find('.prix-input').val()) || 0;
            grandTotal += qty * price;
        });
        $('#grandTotalDisplay').text(grandTotal.toLocaleString('fr-FR'));
    }

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
