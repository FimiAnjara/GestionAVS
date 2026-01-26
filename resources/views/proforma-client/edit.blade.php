@extends('layouts.app')

@section('title', 'Modifier la Proforma Client')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square me-2" style="color: #0056b3;"></i>
                Modifier la Proforma : {{ $proforma->id_proforma_client }}
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('proforma-client.update', $proforma->id_proforma_client) }}" method="POST" id="proformaForm">
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
                        <label for="id_client" class="form-label">
                            <i class="bi bi-person me-2" style="color: #0056b3;"></i>
                            Client *
                        </label>
                        <select class="form-select searchable-select @error('id_client') is-invalid @enderror"
                            id="id_client" name="id_client" required>
                            <option value="">-- Sélectionner un client --</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id_client }}"
                                    {{ old('id_client', $proforma->id_client) == $client->id_client ? 'selected' : '' }}>
                                    {{ $client->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_client')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <label for="id_magasin" class="form-label">
                            <i class="bi bi-shop me-2" style="color: #0056b3;"></i>
                            Magasin Source
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
                                @foreach ($proforma->proformaClientFille as $index => $line)
                                <tr class="article-row">
                                    <td class="text-center article-photo-container">
                                        @if($line->article?->photo)
                                            <img src="{{ asset('storage/' . $line->article->photo) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
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
                                                    {{ old('articles.'.$index.'.id_article', $line->id_article) == $article->id_article ? 'selected' : '' }}>
                                                    {{ $article->id_article }} - {{ $article->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center article-unite text-muted small">{{ $line->article?->unite?->libelle ?? '-' }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm quantite-input @error('articles.'.$index.'.quantite') is-invalid @enderror"
                                            name="articles[{{ $index }}][quantite]" value="{{ old('articles.'.$index.'.quantite', $line->quantite) }}" placeholder="0" min="0.01" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm prix-input @error('articles.'.$index.'.prix') is-invalid @enderror"
                                            name="articles[{{ $index }}][prix]" value="{{ old('articles.'.$index.'.prix', $line->prix) }}" placeholder="0" min="0" step="0.01" required>
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        <span class="row-total">{{ number_format($line->quantite * $line->prix, 0, ',', ' ') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-remove-article">
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
                                        {{ number_format($proforma->proformaClientFille->sum(fn($l) => $l->quantite * $l->prix), 0, ',', ' ') }}
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
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body d-flex gap-3 justify-content-end">
                        <a href="{{ route('proforma-client.list') }}" class="btn btn-secondary px-4">
                            <i class="bi bi-x-lg me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Mettre à jour la Proforma
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    const articlesJS = @json($articlesJS);
    const magasins = @json($magasins->map(fn($m) => ['id' => $m->id_magasin, 'id_entite' => $m->site?->id_entite]));
    
    initSelect2();
    
    let articleCount = {{ $proforma->proformaClientFille->count() }};

    // Initial filtering
    const initialMagasinId = $('#id_magasin').val();
    if (initialMagasinId) {
        const selectedMagasin = magasins.find(m => m.id === initialMagasinId);
        const entiteId = selectedMagasin ? selectedMagasin.id_entite : null;
        $('.article-select').each(function() {
            filterArticleDropdown($(this), entiteId, false);
        });
    }

    // Filter logic
    $('#id_magasin').on('change', function() {
        const magasinId = $(this).val();
        const selectedMagasin = magasins.find(m => m.id === magasinId);
        const entiteId = selectedMagasin ? selectedMagasin.id_entite : null;
        
        $('.article-select').each(function() {
            filterArticleDropdown($(this), entiteId);
        });
    });

    function filterArticleDropdown(selectElement, entiteId, resetIfInvalid = true) {
        const currentValue = selectElement.val();
        selectElement.empty().append('<option value="">-- Sélectionner --</option>');
        
        const filteredArticles = entiteId 
            ? articlesJS.filter(a => a.id_entite === entiteId)
            : articlesJS;

        filteredArticles.forEach(art => {
            const isSelected = art.id === currentValue ? 'selected' : '';
            selectElement.append(`
                <option value="${art.id}" 
                    data-unite="${art.unite}" 
                    data-photo="${art.photo}"
                    ${isSelected}>
                    ${art.id} - ${art.nom}
                </option>
            `);
        });

        if (currentValue && !filteredArticles.find(a => a.id === currentValue)) {
            if (resetIfInvalid) selectElement.val('').trigger('change');
        } else {
            selectElement.trigger('change.select2');
        }
    }

    $('#btnAddArticle').on('click', function() {
        const container = document.getElementById('articlesContainer');
        const magasinId = $('#id_magasin').val();
        const selectedMagasin = magasins.find(m => m.id === magasinId);
        const entiteId = selectedMagasin ? selectedMagasin.id_entite : null;

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
        
        const newSelect = $(container).find('.article-select:last');
        filterArticleDropdown(newSelect, entiteId);
        
        newSelect.select2({
            language: 'fr',
            placeholder: '-- Sélectionner --',
            allowClear: true,
            width: '100%'
        });
        
        articleCount++;
        updateRemoveButtons();
    });

    $(document).on('click', '.btn-remove-article', function(e) {
        e.preventDefault();
        $(this).closest('.article-row').remove();
        updateRemoveButtons();
        calculateGrandTotal();
    });

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
