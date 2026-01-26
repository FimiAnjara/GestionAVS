@extends('layouts.app')

@section('title', 'Créer un Bon de Livraison Client')

@section('header-buttons')
    <a href="{{ route('bon-livraison-client.list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Retour
    </a>
@endsection

@section('content')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-truck me-2" style="color: #0056b3;"></i>
                Nouveau Bon de Livraison Client
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('bon-livraison-client.store') }}" method="POST">
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
                        <label for="id_bon_commande_client" class="form-label">
                            <i class="bi bi-file-earmark-arrow-up me-2" style="color: #0056b3;"></i>
                            Bon de Commande Client <span class="text-danger">*</span>
                        </label>
                        <select class="form-select searchable-select @error('id_bon_commande_client') is-invalid @enderror" 
                            id="id_bon_commande_client" name="id_bon_commande_client" required>
                            <option value="">-- Sélectionner un bon --</option>
                            @foreach ($bonCommandes as $bc)
                                <option value="{{ $bc->id_bon_commande_client }}"
                                    {{ (old('id_bon_commande_client') == $bc->id_bon_commande_client || request('bon_commande_id') == $bc->id_bon_commande_client) ? 'selected' : '' }}>
                                    {{ $bc->id_bon_commande_client }} - {{ $bc->client->nom }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Seuls les bons validés</small>
                        @error('id_bon_commande_client') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-4 text-end d-flex align-items-end justify-content-end">
                        <div class="bg-light p-2 rounded border w-100 text-start">
                            <small class="text-muted d-block">Client :</small>
                            <strong id="client-display">{{ $bonCommandePreselected->client->nom ?? '---' }}</strong>
                            <input type="hidden" name="id_client" id="id_client" value="{{ old('id_client', $bonCommandePreselected?->id_client) }}">
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
                            id="id_magasin" name="id_magasin" required>
                            <option value="">-- Sélectionner un magasin --</option>
                            @foreach ($magasins as $mag)
                                <option value="{{ $mag->id_magasin }}" {{ old('id_magasin', $bonCommandePreselected?->id_magasin) == $mag->id_magasin ? 'selected' : '' }}>
                                    [{{ $mag->site?->entite?->nom ?? 'N/A' }}] {{ $mag->site?->localisation ?? 'N/A' }} - {{ $mag->nom ?? $mag->designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_magasin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-lg-6 text-end d-flex align-items-end justify-content-end">
                        <div class="bg-light p-2 rounded border w-100 text-start">
                            <small class="text-muted d-block">Description (optionnel) :</small>
                            <textarea class="form-control" id="description" name="description" rows="1">{{ old('description', $bonCommandePreselected?->description) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Articles -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="bi bi-box me-2" style="color: #0056b3;"></i>
                        Articles à Livrer <span class="text-danger">*</span>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">Photo</th>
                                    <th>Article</th>
                                    <th class="text-center" width="8%">Unité</th>
                                    <th class="text-center" width="15%">Quantité</th>
                                    <th class="text-center" width="8%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="articles-container">
                                @if($bonCommandePreselected && $bonCommandePreselected->bonCommandeClientFille->count() > 0)
                                    @foreach($bonCommandePreselected->bonCommandeClientFille as $index => $item)
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
                                            <td class="text-center">
                                                <span class="badge bg-success article-unite">{{ $item->article?->unite?->libelle ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm" name="articles[{{ $index }}][quantite]" 
                                                    value="{{ old('articles.'.$index.'.quantite', $item->quantite) }}" placeholder="0" min="0.01" step="any" required>
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
                                        <td class="text-center">
                                            <span class="badge bg-success article-unite">-</span>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm" name="articles[0][quantite]" placeholder="0" min="0.01" step="any" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-article" style="display: none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="btn-add-article">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                    </button>
                </div>
                
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>Note : L'enregistrement de ce bon générera une <strong>Sortie de Stock</strong> automatique.
                </div>

                <!-- Boutons -->
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body d-flex gap-3 justify-content-end">
                        <a href="{{ route('bon-livraison-client.list') }}" class="btn btn-secondary px-4">
                            <i class="bi bi-x-lg me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer la Livraison
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
        const articlesJS = @json($articlesJS);
        const magasins = @json($magasins->map(fn($m) => ['id' => $m->id_magasin, 'id_entite' => $m->site?->id_entite]));
        
        $(document).ready(function() {
            initSelect2();

            // Filter logic
            $('#id_magasin').on('change', function() {
                const magasinId = $(this).val();
                const selectedMagasin = magasins.find(m => m.id === magasinId);
                const entiteId = selectedMagasin ? selectedMagasin.id_entite : null;
                
                $('.article-select').each(function() {
                    filterArticleDropdown($(this), entiteId);
                });
            });

            function filterArticleDropdown(selectElement, entiteId) {
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
                    selectElement.val('').trigger('change');
                } else {
                    selectElement.trigger('change.select2');
                }
            }
            
            // Écouter le changement du bon de commande
            $('#id_bon_commande_client').on('change select2:select', function() {
                const bcId = $(this).val();
                if (bcId) {
                    $.ajax({
                        url: '{{ route("bon-livraison-client.api.bon-commande", ":id") }}'.replace(':id', bcId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#client-display').text(data.client_nom);
                            $('#id_client').val(data.client_id);
                            
                            if (data.id_magasin) {
                                $('#id_magasin').val(data.id_magasin).trigger('change');
                            }

                            if (data.description) {
                                $('#description').val(data.description);
                            }
                            
                            const container = document.getElementById('articles-container');
                            container.innerHTML = ''; 
                            if (data.articles && data.articles.length > 0) {
                                const selectedMagasin = magasins.find(m => m.id === data.id_magasin);
                                const entiteId = selectedMagasin ? selectedMagasin.id_entite : null;

                                data.articles.forEach((article, index) => {
                                    addArticleRow(article, index, entiteId);
                                });
                            }
                        }
                    });
                }
            });

            $('#btn-add-article').on('click', function() {
                const index = $('.article-row').length;
                const magasinId = $('#id_magasin').val();
                const selectedMagasin = magasins.find(m => m.id === magasinId);
                const entiteId = selectedMagasin ? selectedMagasin.id_entite : null;
                addArticleRow(null, index, entiteId);
            });

            $(document).on('click', '.btn-remove-article', function() { $(this).closest('tr').remove(); updateRemoveButtons(); });

            $(document).on('change', '.article-select', function() {
                const selected = $(this).find(':selected');
                const row = $(this).closest('tr');
                row.find('.article-unite').text(selected.data('unite') || '-');
                const photo = selected.data('photo');
                row.find('.article-photo-container').html(photo ? `<img src="${photo}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">` : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`);
            });

            function addArticleRow(data = null, index, entiteId = null) {
                const container = document.getElementById('articles-container');
                const rowHtml = `
                    <tr class="article-row">
                        <td class="text-center article-photo-container">
                            ${data && data.photo ? `<img src="${data.photo}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">` : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`}
                        </td>
                        <td>
                            <select class="form-select form-select-sm searchable-select article-select" name="articles[${index}][id_article]" required>
                                <option value="">-- Sélectionner --</option>
                            </select>
                        </td>
                        <td class="text-center"><span class="badge bg-success article-unite">${data ? (data.unite || '-') : '-'}</span></td>
                        <td><input type="number" class="form-control form-control-sm" name="articles[${index}][quantite]" value="${data ? data.quantite : ''}" placeholder="0" min="0.01" step="any" required></td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-article"><i class="bi bi-trash"></i></button></td>
                    </tr>
                `;
                container.insertAdjacentHTML('beforeend', rowHtml);
                
                const newSelect = $(container).find('.article-select:last');
                filterArticleDropdown(newSelect, entiteId);
                if (data) {
                    newSelect.val(data.id_article).trigger('change.select2');
                }
                
                initSelect2(newSelect);
                updateRemoveButtons();
            }

            function updateRemoveButtons() { $('.btn-remove-article').toggle($('.article-row').length > 1); }
            function initSelect2(element = null) { (element || $('.searchable-select')).select2({ language: 'fr', width: '100%', placeholder: '-- Sélectionner --' }); }
            
            // Trigger load if initial ID present
            if ($('#id_bon_commande_client').val()) { $('#id_bon_commande_client').trigger('change'); }
        });
    </script>
@endsection
