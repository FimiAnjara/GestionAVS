@extends('layouts.app')

@section('title', 'Créer un Bon de Réception')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('header-buttons')
    <a href="{{ route('bon-reception.list') }}" class="btn btn-secondary">
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
                <i class="bi bi-file-earmark-check me-2" style="color: #0056b3;"></i>
                Nouveau Bon de Réception
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('bon-reception.store') }}" method="POST">
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
                        <label for="id_bonCommande" class="form-label">
                            <i class="bi bi-file-earmark-arrow-up me-2" style="color: #0056b3;"></i>
                            Bon de Commande <span class="text-danger">*</span>
                        </label>
                        <select class="form-select searchable-select @error('id_bonCommande') is-invalid @enderror" 
                            id="id_bonCommande" name="id_bonCommande" required>
                            <option value="">-- Sélectionner un bon --</option>
                            @foreach ($bonCommandes as $bc)
                                <option value="{{ $bc->id_bonCommande }}"
                                    {{ old('id_bonCommande') == $bc->id_bonCommande ? 'selected' : '' }}>
                                    {{ $bc->id_bonCommande }} - {{ $bc->proformaFournisseur->fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Seuls les bons validés par DG</small>
                        @error('id_bonCommande') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-4 text-end d-flex align-items-end justify-content-end">
                        <div class="bg-light p-2 rounded border w-100 text-start">
                            <small class="text-muted d-block">Fournisseur :</small>
                            <strong id="fournisseur-display">---</strong>
                            <input type="hidden" name="id_fournisseur" id="id_fournisseur" value="{{ old('id_fournisseur') }}">
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
                            id="id_magasin" name="id_magasin" required>
                            <option value="">-- Sélectionner un magasin --</option>
                            @foreach ($magasins as $mag)
                                <option value="{{ $mag->id_magasin }}" {{ old('id_magasin') == $mag->id_magasin ? 'selected' : '' }}>
                                    [{{ $mag->site?->entite?->nom ?? 'N/A' }}] {{ $mag->site?->localisation ?? 'N/A' }} - {{ $mag->nom ?? $mag->designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_magasin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-lg-6 text-end d-flex align-items-end justify-content-end">
                        <div class="bg-light p-2 rounded border w-100 text-start">
                            <small class="text-muted d-block">État Initial :</small>
                            <span class="badge bg-warning">En attente de réception</span>
                            <input type="hidden" name="etat" value="1">
                        </div>
                    </div>
                </div>
                
                <!-- Articles -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="bi bi-box me-2" style="color: #0056b3;"></i>
                        Articles à Réceptionner <span class="text-danger">*</span>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">Photo</th>
                                    <th>Article</th>
                                    <th class="text-center" width="8%">Unité</th>
                                    <th class="text-center" width="15%">Quantité</th>
                                    <th class="text-center" width="18%">Date Expiration</th>
                                    <th class="text-center" width="8%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="articles-container">
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
                                        <input type="number" class="form-control form-control-sm" name="articles[0][quantite]" placeholder="0" min="0.01" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control form-control-sm" name="articles[0][date_expiration]">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-remove-article" style="display: none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="btn-add-article">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                    </button>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer la Réception
                    </button>
                    <a href="{{ route('bon-reception.list') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        const articlesData = @json($articlesJS);
        
        $(document).ready(function() {
            initSelect2();
            
            // Écouter le changement du bon de commande
            $('#id_bonCommande').on('change select2:select', function() {
                const bcId = $(this).val();
                
                if (bcId) {
                    $.ajax({
                        url: '{{ route("bon-reception.api.bon-commande", ":id") }}'.replace(':id', bcId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#fournisseur-display').text(data.fournisseur_nom);
                            $('#id_fournisseur').val(data.fournisseur_id);
                            
                            if (data.id_magasin) {
                                $('#id_magasin').val(data.id_magasin).trigger('change');
                            }
                            
                            const container = document.getElementById('articles-container');
                            container.innerHTML = ''; 
                            
                            if (data.articles && data.articles.length > 0) {
                                data.articles.forEach((article, index) => {
                                    addArticleRow(article, index);
                                });
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
                            <input type="number" class="form-control form-control-sm" name="articles[${index}][quantite]" value="${data ? data.quantite : ''}" placeholder="0" min="0.01" step="0.01" required>
                        </td>
                        <td>
                            <input type="date" class="form-control form-control-sm" name="articles[${index}][date_expiration]">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-danger btn-remove-article">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                container.insertAdjacentHTML('beforeend', rowHtml);
                initSelect2($(container).find('.searchable-select:last'));
                updateRemoveButtons();
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

            updateRemoveButtons();
        });
    </script>
@endsection
