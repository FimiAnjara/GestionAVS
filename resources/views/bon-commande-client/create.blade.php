@extends('layouts.app')

@section('title', 'Créer un Bon de Commande Client')

@section('header-buttons')
    <a href="{{ route('bon-commande-client.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-arrow-up me-2" style="color: #0056b3;"></i>
                Nouveau Bon de Commande Client
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('bon-commande-client.store') }}" method="POST">
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
                        <label for="id_proforma_client" class="form-label">
                            <i class="bi bi-file-earmark-check me-2" style="color: #0056b3;"></i>
                            Proforma Sourcée
                        </label>
                        <select class="form-select searchable-select @error('id_proforma_client') is-invalid @enderror" 
                            id="id_proforma_client" name="id_proforma_client">
                            <option value="">-- Sélectionner une proforma (optionnel) --</option>
                            @foreach ($proformas as $proforma)
                                <option value="{{ $proforma->id_proforma_client }}"
                                    {{ old('id_proforma_client', $proformaClient?->id_proforma_client) == $proforma->id_proforma_client ? 'selected' : '' }}>
                                    {{ $proforma->id_proforma_client }} - {{ $proforma->client->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_proforma_client') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-4 text-end d-flex align-items-end justify-content-end">
                        <div class="bg-light p-2 rounded border w-100 text-start">
                            <small class="text-muted d-block">Client :</small>
                            <strong id="client-display">{{ $proformaClient->client->nom ?? '---' }}</strong>
                            <input type="hidden" name="id_client" id="id_client" value="{{ old('id_client', $proformaClient?->id_client) }}">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="id_magasin" class="form-label">
                            <i class="bi bi-shop me-2" style="color: #0056b3;"></i>
                            Magasin Source
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
                                                    value="{{ old('articles.'.$index.'.prix', $item->prix) }}" required>
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                <span class="row-total">{{ number_format($item->quantite * $item->prix, 0, ',', ' ') }}</span>
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
                
                <!-- Boutons -->
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body d-flex gap-3 justify-content-end">
                        <a href="{{ route('bon-commande-client.list') }}" class="btn btn-secondary px-4">
                            <i class="bi bi-x-lg me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer le Bon
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    <script>
        const articlesData = @json($articlesJS);
        
        $(document).ready(function() {
            initSelect2();
            
            $('#id_proforma_client').on('change select2:select', function() {
                const proformaId = $(this).val();
                if (proformaId) {
                    $.ajax({
                        url: '{{ route("bon-commande-client.api.proforma", ":id") }}'.replace(':id', proformaId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#client-display').text(data.client_nom);
                            $('#id_client').val(data.client_id);
                            if (data.id_magasin) $('#id_magasin').val(data.id_magasin).trigger('change');
                            if (data.description) $('#description').val(data.description);
                            
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

            $('#btn-add-article').on('click', function() {
                const index = $('.article-row').length;
                addArticleRow(null, index);
            });

            $(document).on('click', '.btn-remove-article', function() {
                $(this).closest('tr').remove();
                updateRemoveButtons();
                calculateGrandTotal();
            });

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
