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
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-file-earmark-plus me-2"></i>Créer un Bon de Commande
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('bon-commande.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="date_" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                            id="date_" name="date_" value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                        @error('date_') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-lg-6">
                        <label for="id_fournisseur" class="form-label">Fournisseur <span class="text-danger">*</span></label>
                        <select class="form-select select2-fournisseur @error('id_fournisseur') is-invalid @enderror" 
                            id="id_fournisseur" name="id_fournisseur" required>
                            <option value="">-- Sélectionner un fournisseur --</option>
                            @foreach ($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id_fournisseur }}" 
                                    {{ old('id_fournisseur') == $fournisseur->id_fournisseur ? 'selected' : '' }}
                                    {{ $proformaFournisseur && $proformaFournisseur->id_fournisseur == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                    {{ $fournisseur->id_fournisseur }} - {{ $fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_fournisseur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <label for="id_proformaFournisseur" class="form-label">Proforma <span class="text-danger">*</span></label>
                        <select class="form-select select2-proforma @error('id_proformaFournisseur') is-invalid @enderror" 
                            id="id_proformaFournisseur" name="id_proformaFournisseur" required>
                            <option value="">-- Sélectionner une proforma --</option>
                            @foreach ($proformas as $proforma)
                                <option value="{{ $proforma->id_proformaFournisseur }}"
                                    {{ old('id_proformaFournisseur', $proformaFournisseur?->id_proformaFournisseur) == $proforma->id_proformaFournisseur ? 'selected' : '' }}>
                                    {{ $proforma->id_proformaFournisseur }} - {{ $proforma->fournisseur->nom }} ({{ $proforma->etat_label }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Seules les proformas validées par Finance sont affichées</small>
                        @error('id_proformaFournisseur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                        id="description" name="description" rows="3">{{ old('description', $descriptionProforma) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <!-- Articles -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="bi bi-box me-2"></i>Articles <span class="text-danger">*</span>
                    </h6>
                    <div id="articles-container">
                        @if ($articlesProforma && count($articlesProforma) > 0)
                            @foreach ($articlesProforma as $index => $articleProforma)
                                <div class="article-row mb-3 p-3 border rounded" data-row="{{ $index }}">
                                    <div class="row g-2">
                                        <div class="col-lg-6">
                                            <label class="form-label">Article</label>
                                            <select class="form-select select2-article" name="articles[{{ $index }}][id_article]" required>
                                                <option value="">-- Sélectionner --</option>
                                                @foreach ($articles as $article)
                                                    <option value="{{ $article->id_article }}" 
                                                        {{ $articleProforma->id_article == $article->id_article ? 'selected' : '' }}>
                                                        {{ $article->id_article }} - {{ $article->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label class="form-label">Quantité</label>
                                            <input type="number" class="form-control" name="articles[{{ $index }}][quantite]" 
                                                value="{{ $articleProforma->quantite }}"
                                                placeholder="0" min="1" step="0.01" required>
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="form-label">Prix (Ar)</label>
                                            <input type="number" class="form-control" name="articles[{{ $index }}][prix]" 
                                                value="{{ $articleProforma->prix_achat }}"
                                                placeholder="0" min="0" step="0.01" required>
                                        </div>
                                        <div class="col-lg-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-article w-100">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="article-row mb-3 p-3 border rounded" data-row="0">
                                <div class="row g-2">
                                    <div class="col-lg-6">
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
                                    <div class="col-lg-2">
                                        <label class="form-label">Prix (Ar)</label>
                                        <input type="number" class="form-control" name="articles[0][prix]" 
                                            placeholder="0" min="0" step="0.01" required>
                                    </div>
                                    <div class="col-lg-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-article w-100" style="display:none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm" id="btn-add-article">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                    </button>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Créer
                    </button>
                    <a href="{{ route('bon-commande.list') }}" class="btn btn-secondary">
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
        const articlesData = @json($articles->map(fn($a) => ['id' => $a->id_article, 'nom' => $a->nom])->values());
        
        $(document).ready(function() {
            // Initialiser Select2 pour fournisseur et proforma
            $('.select2-fournisseur, .select2-proforma').select2({
                language: 'fr',
                width: '100%',
            });
            
            // Initialiser Select2 pour les articles existants
            $('.select2-article').select2({
                language: 'fr',
                width: '100%',
            });
            
            // Écouter le changement du select proforma
            $('#id_proformaFournisseur').on('change', function() {
                const proformaId = $(this).val();
                
                if (proformaId) {
                    // Récupérer les données de la proforma
                    $.ajax({
                        url: '{{ route("bon-commande.api.proforma", ":id") }}'.replace(':id', proformaId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            // Pré-remplir le fournisseur
                            $('#id_fournisseur').val(data.fournisseur_id).trigger('change');
                            
                            // Pré-remplir la description avec ID proforma + description de la proforma
                            const newDescription = 'Demande d\'achat ' + proformaId + '\n' + (data.description ? data.description : '');
                            $('#description').val(newDescription);
                            
                            // Pré-remplir les articles
                            const container = document.getElementById('articles-container');
                            container.innerHTML = ''; // Vider les articles existants
                            rowCount = 0; // Réinitialiser le compteur
                            
                            if (data.articles && data.articles.length > 0) {
                                data.articles.forEach((article, index) => {
                                    // Créer les options d'articles
                                    let articleOptions = '<option value="">-- Sélectionner --</option>';
                                    articlesData.forEach(art => {
                                        const selected = art.id === article.id_article ? 'selected' : '';
                                        articleOptions += `<option value="${art.id}" ${selected}>${art.id} - ${art.nom}</option>`;
                                    });
                                    
                                    const articleHtml = `
                                        <div class="article-row mb-3 p-3 border rounded" data-row="${index}">
                                            <div class="row g-2">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Article</label>
                                                    <select class="form-select select2-article" name="articles[${index}][id_article]" required>
                                                        ${articleOptions}
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control" name="articles[${index}][quantite]" 
                                                        value="${article.quantite}"
                                                        placeholder="0" min="1" step="0.01" required>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label class="form-label">Prix (Ar)</label>
                                                    <input type="number" class="form-control" name="articles[${index}][prix]" 
                                                        value="${article.prix}"
                                                        placeholder="0" min="0" step="0.01" required>
                                                </div>
                                                <div class="col-lg-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-article w-100">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    container.insertAdjacentHTML('beforeend', articleHtml);
                                    rowCount++;
                                });
                                
                                // Réinitialiser Select2 pour les nouveaux articles
                                $('select.select2-article').select2({
                                    language: 'fr',
                                    width: '100%',
                                });
                                
                                // Ajouter les listeners de suppression
                                document.querySelectorAll('.btn-remove-article').forEach(btn => {
                                    btn.addEventListener('click', function() {
                                        this.closest('.article-row').remove();
                                        updateRemoveButtons();
                                    });
                                });
                                
                                updateRemoveButtons();
                            }
                        },
                        error: function(xhr) {
                            alert('Erreur lors du chargement de la proforma');
                        }
                    });
                }
            });
        });
        
        let rowCount = {{ count($articlesProforma) > 0 ? count($articlesProforma) : 1 }};
        
        document.getElementById('btn-add-article').addEventListener('click', function() {
            const container = document.getElementById('articles-container');
            const newRow = document.querySelector('.article-row').cloneNode(true);
            
            newRow.setAttribute('data-row', rowCount);
            newRow.querySelectorAll('input, select').forEach(input => {
                const name = input.name.replace(/\[\d+\]/, '[' + rowCount + ']');
                input.name = name;
                input.value = '';
            });
            
            // Réinitialiser Select2 pour la nouvelle ligne
            const newSelect = newRow.querySelector('.select2-article');
            if (newSelect) {
                $(newSelect).off('select2:opening selectAll');
                newSelect.className = 'form-select select2-article';
            }
            
            newRow.querySelectorAll('.btn-remove-article').forEach(btn => {
                btn.style.display = 'block';
                btn.addEventListener('click', function() {
                    newRow.remove();
                    updateRemoveButtons();
                });
            });
            
            container.appendChild(newRow);
            
            // Initialiser Select2 sur le nouveau select
            $(newRow).find('.select2-article').select2({
                language: 'fr',
                width: '100%',
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
        
        // Initialiser les boutons de suppression
        updateRemoveButtons();
        document.querySelectorAll('.btn-remove-article').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.article-row').remove();
                updateRemoveButtons();
            });
        });
    </script>
@endsection
