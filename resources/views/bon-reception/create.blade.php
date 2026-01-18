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
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-check me-2"></i>Créer un Bon de Réception
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('bon-reception.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-lg-3">
                        <label for="date_" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                            id="date_" name="date_" value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                        @error('date_') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-lg-3">
                        <label for="id_bonCommande" class="form-label">Bon de Commande <span class="text-danger">*</span></label>
                        <select class="form-select select2-commande @error('id_bonCommande') is-invalid @enderror" 
                            id="id_bonCommande" name="id_bonCommande" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach ($bonCommandes as $bc)
                                <option value="{{ $bc->id_bonCommande }}"
                                    data-fournisseur="{{ $bc->proformaFournisseur?->fournisseur?->id_fournisseur }}"
                                    data-fournisseur-nom="{{ $bc->proformaFournisseur?->fournisseur?->nom }}"
                                    data-articles="{{ json_encode($bc->bonCommandeFille->map(function($item) { return ['id_article' => $item->id_article, 'quantite' => $item->quantite, 'nom' => $item->article?->nom ?? $item->id_article]; })->toArray()) }}"
                                    {{ old('id_bonCommande') == $bc->id_bonCommande ? 'selected' : '' }}>
                                    {{ $bc->id_bonCommande }} - {{ $bc->proformaFournisseur->fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Seuls les bons validés</small>
                        @error('id_bonCommande') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-lg-3">
                        <label for="id_fournisseur" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                        <select class="form-select select2-fournisseur @error('id_fournisseur') is-invalid @enderror" 
                            id="id_fournisseur" name="id_fournisseur" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach ($fournisseurs ?? [] as $fournisseur)
                                <option value="{{ $fournisseur->id_fournisseur }}" {{ old('id_fournisseur') == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                    {{ $fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_fournisseur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-lg-3">
                        <label for="id_magasin" class="form-label">Magasin <span class="text-danger">*</span></label>
                        <select class="form-select select2-magasin @error('id_magasin') is-invalid @enderror" 
                            id="id_magasin" name="id_magasin" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach ($magasins ?? [] as $mag)
                                <option value="{{ $mag->id_magasin }}" {{ old('id_magasin') == $mag->id_magasin ? 'selected' : '' }}>
                                    {{ $mag->nom ?? $mag->designation }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_magasin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <!-- État caché -->
                <input type="hidden" name="etat" id="etat" value="1">
                
                <!-- Articles -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="bi bi-box me-2"></i>Articles <span class="text-danger">*</span>
                    </h6>
                    <div id="articles-container">
                        <div class="article-row mb-3 p-3 border rounded" data-row="0">
                            <div class="row g-2">
                                <div class="col-lg-5">
                                    <label class="form-label">Article</label>
                                    <select class="form-select select2-article" name="articles[0][id_article]" required>
                                        <option value="">-- Sélectionner --</option>
                                        @foreach ($articles as $article)
                                            <option value="{{ $article->id_article }}">
                                                {{ $article->id_article }} - {{ $article->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" class="form-control" name="articles[0][quantite]" 
                                        placeholder="0" min="1" step="0.01" required>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Date Expiration</label>
                                    <input type="date" class="form-control" name="articles[0][date_expiration]">
                                </div>
                                <div class="col-lg-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-article w-100" style="display:none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm" id="btn-add-article">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                    </button>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Créer
                    </button>
                    <a href="{{ route('bon-reception.list') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.select2-commande, .select2-article, .select2-magasin, .select2-fournisseur').select2({
                language: 'fr',
                width: '100%',
            });
            
            // Quand on sélectionne un bon de commande
            $('#id_bonCommande').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const idFournisseur = selectedOption.data('fournisseur');
                const articles = selectedOption.data('articles');
                
                console.log('Articles reçus:', articles);
                
                // Remplir le fournisseur avec Select2
                if (idFournisseur) {
                    $('#id_fournisseur').val(idFournisseur).trigger('change');
                } else {
                    $('#id_fournisseur').val('').trigger('change');
                }
                
                // Remplir les articles
                if (articles && articles.length > 0) {
                    const container = document.getElementById('articles-container');
                    container.innerHTML = ''; // Vider le conteneur
                    
                    articles.forEach((article, index) => {
                        const articleRow = document.createElement('div');
                        articleRow.className = 'article-row mb-3 p-3 border rounded';
                        articleRow.setAttribute('data-row', index);
                        articleRow.innerHTML = `
                            <div class="row g-2">
                                <div class="col-lg-5">
                                    <label class="form-label">Article</label>
                                    <select class="form-select select2-article" name="articles[${index}][id_article]" required>
                                        <option value="${article.id_article}" selected>
                                            ${article.id_article} - ${article.nom}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" class="form-control" name="articles[${index}][quantite]" 
                                        value="${article.quantite}" min="1" step="0.01" required>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Date Expiration</label>
                                    <input type="date" class="form-control" name="articles[${index}][date_expiration]">
                                </div>
                                <div class="col-lg-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-article w-100" style="display:${articles.length > 1 ? 'block' : 'none'};">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        container.appendChild(articleRow);
                        
                        // Initialiser Select2 pour ce nouveau select
                        $(articleRow).find('.select2-article').select2({
                            language: 'fr',
                            width: '100%',
                        });
                        
                        // Ajouter écouteur pour suppression
                        articleRow.querySelector('.btn-remove-article').addEventListener('click', function(e) {
                            e.preventDefault();
                            articleRow.remove();
                            updateRemoveButtons();
                        });
                    });
                    
                    rowCount = articles.length;
                    updateRemoveButtons();
                }
            });
        });
        
        let rowCount = 1;
        
        document.getElementById('btn-add-article').addEventListener('click', function() {
            const container = document.getElementById('articles-container');
            const newRow = document.createElement('div');
            newRow.className = 'article-row mb-3 p-3 border rounded';
            newRow.setAttribute('data-row', rowCount);
            newRow.innerHTML = `
                <div class="row g-2">
                    <div class="col-lg-5">
                        <label class="form-label">Article</label>
                        <select class="form-select select2-article" name="articles[${rowCount}][id_article]" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach ($articles as $article)
                                <option value="{{ $article->id_article }}">
                                    {{ $article->id_article }} - {{ $article->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">Quantité</label>
                        <input type="number" class="form-control" name="articles[${rowCount}][quantite]" 
                            placeholder="0" min="1" step="0.01" required>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">Date Expiration</label>
                        <input type="date" class="form-control" name="articles[${rowCount}][date_expiration]">
                    </div>
                    <div class="col-lg-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-article w-100" style="display:none;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(newRow);
            
            $(newRow).find('.select2-article').select2({
                language: 'fr',
                width: '100%',
            });
            
            newRow.querySelector('.btn-remove-article').addEventListener('click', function(e) {
                e.preventDefault();
                newRow.remove();
                updateRemoveButtons();
            });
            
            rowCount++;
            updateRemoveButtons();
        });
        
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.article-row');
            rows.forEach(row => {
                const btn = row.querySelector('.btn-remove-article');
                btn.style.display = rows.length > 1 ? 'block' : 'none';
            });
        }
        
        updateRemoveButtons();
        document.querySelectorAll('.btn-remove-article').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.closest('.article-row').remove();
                updateRemoveButtons();
            });
        });
    </script>
@endsection
