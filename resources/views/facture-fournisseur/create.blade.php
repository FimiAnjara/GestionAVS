@extends('layouts.app')

@section('title', 'Créer une Facture Fournisseur')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
    @if($bonCommande)
    <!-- Formulaire de Création avec Bon de Commande -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark me-2"></i>Nouvelle Facture Fournisseur
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="factureForm" method="POST" action="{{ route('facture-fournisseur.store') }}">
                        @csrf
                        
                        <!-- Bon de Commande -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-receipt me-2"></i><strong>Bon de Commande</strong>
                            </label>
                            <input type="text" class="form-control" value="{{ $bonCommande->id_bonCommande }}" disabled>
                            <input type="hidden" name="id_bonCommande" value="{{ $bonCommande->id_bonCommande }}">
                        </div>

                        <!-- Fournisseur -->
                        <div class="mb-4">
                            <label for="id_fournisseur" class="form-label">
                                <i class="bi bi-building me-2"></i>Fournisseur <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2-fournisseur @error('id_fournisseur') is-invalid @enderror" 
                                    id="id_fournisseur" name="id_fournisseur" required>
                                <option value="">-- Sélectionner un fournisseur --</option>
                                @php
                                    $fournisseurs = \App\Models\Fournisseur::all();
                                    $defaultFournisseur = $bonCommande->proformaFournisseur?->id_fournisseur;
                                @endphp
                                @foreach ($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id_fournisseur }}" 
                                            {{ (old('id_fournisseur') ?? $defaultFournisseur) == $fournisseur->id_fournisseur ? 'selected' : '' }}>
                                        {{ $fournisseur->id_fournisseur }} - {{ $fournisseur->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_fournisseur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date Facture -->
                        <div class="mb-4">
                            <label for="date_" class="form-label">
                                <i class="bi bi-calendar-event me-2"></i>Date de Facture <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('date_') is-invalid @enderror" 
                                   id="date_" name="date_" 
                                   value="{{ old('date_', now()->format('Y-m-d')) }}" required>
                            @error('date_')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text me-2"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Notes ou observations">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Créer la Facture
                            </button>
                            <a href="{{ route('facture-fournisseur.list') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Articles Éditables -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i>Articles
                        </h5>
                        <button type="button" class="btn btn-light btn-sm" id="btn-add-article">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="articles-container">
                        @php $index = 0; @endphp
                        @foreach($bonCommande->bonCommandeFille as $ligne)
                        <div class="article-row p-3 border-bottom" data-row="{{ $index }}">
                            <div class="row g-2 mb-2">
                                <div class="col-12">
                                    <select class="form-select form-select-sm select2-article" name="articles[{{ $index }}][id_article]" required>
                                        <option value="">-- Sélectionner un article --</option>
                                        @php $articles = \App\Models\Article::all(); @endphp
                                        @foreach ($articles as $article)
                                            <option value="{{ $article->id_article }}" 
                                                    {{ $ligne->id_article == $article->id_article ? 'selected' : '' }}>
                                                {{ $article->id_article }} - {{ $article->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-text text-muted small d-block mb-1">Quantité</label>
                                    <input type="number" class="form-control form-control-sm article-qty" name="articles[{{ $index }}][quantite]" 
                                        value="{{ $ligne->quantite }}" min="1" step="0.01" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-text text-muted small d-block mb-1">Prix (Ar)</label>
                                    <input type="number" class="form-control form-control-sm article-price" name="articles[{{ $index }}][prix]" 
                                        value="{{ $ligne->prix_achat }}" min="0" step="0.01" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm btn-remove-article mt-2 w-100" style="{{ $bonCommande->bonCommandeFille->count() == 1 ? 'display:none' : '' }}">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </div>
                        @php $index++; @endphp
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong id="total-articles">0 Ar</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Sélection d'un Bon de Commande -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>Sélectionner un Bon de Commande
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>Veuillez sélectionner un bon de commande validé par DG
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fournisseur</th>
                            <th>Date</th>
                            <th>État</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\BonCommande::where('etat', 11)->where('id_factureFournisseur', null)->with('proformaFournisseur.fournisseur')->latest()->get() as $bc)
                        <tr>
                            <td><strong>{{ $bc->id_bonCommande }}</strong></td>
                            <td>{{ $bc->proformaFournisseur?->fournisseur?->nom ?? 'N/A' }}</td>
                            <td>{{ $bc->date_->format('d/m/Y') }}</td>
                            <td><span class="badge bg-success">Validé par DG</span></td>
                            <td class="text-center">
                                <a href="{{ route('facture-fournisseur.createFromBonCommande', $bc->id_bonCommande) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-check me-2"></i>Utiliser
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucun bon de commande disponible
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('scripts')
<script>
    let articles = @json(\App\Models\Article::all());
    let articleIndex = {{ count($bonCommande->bonCommandeFille ?? []) }};

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.article-row').forEach(row => {
            let qty = parseFloat(row.querySelector('.article-qty').value) || 0;
            let price = parseFloat(row.querySelector('.article-price').value) || 0;
            total += qty * price;
        });
        
        let totalElement = document.getElementById('total-articles');
        totalElement.textContent = new Intl.NumberFormat('fr-FR').format(Math.floor(total)) + ' Ar';
    }

    function createArticleRow(index, articleId = '', qty = '', price = '') {
        const html = `
            <div class="article-row p-3 border-bottom" data-row="${index}">
                <div class="row g-2 mb-2">
                    <div class="col-12">
                        <select class="form-select form-select-sm select2-article" name="articles[${index}][id_article]" required>
                            <option value="">-- Sélectionner un article --</option>
                            ${articles.map(art => `
                                <option value="${art.id_article}" ${articleId === art.id_article ? 'selected' : ''}>
                                    ${art.id_article} - ${art.nom}
                                </option>
                            `).join('')}
                        </select>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-text text-muted small d-block mb-1">Quantité</label>
                        <input type="number" class="form-control form-control-sm article-qty" name="articles[${index}][quantite]" 
                            value="${qty}" min="1" step="0.01" required>
                    </div>
                    <div class="col-6">
                        <label class="form-text text-muted small d-block mb-1">Prix (Ar)</label>
                        <input type="number" class="form-control form-control-sm article-price" name="articles[${index}][prix]" 
                            value="${price}" min="0" step="0.01" required>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm btn-remove-article mt-2 w-100">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </div>
        `;
        return html;
    }

    // Event delegation for article removal and changes
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-article')) {
            e.preventDefault();
            const row = e.target.closest('.article-row');
            
            // Keep at least one article
            if (document.querySelectorAll('.article-row').length > 1) {
                row.remove();
                updateTotal();
            } else {
                alert('Vous devez conserver au moins un article');
            }
        }
    });

    // Listen for changes in quantity and price
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('article-qty') || e.target.classList.contains('article-price')) {
            updateTotal();
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('article-qty') || e.target.classList.contains('article-price')) {
            updateTotal();
        }
    });

    // Add article button
    document.getElementById('btn-add-article').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('articles-container');
        container.insertAdjacentHTML('beforeend', createArticleRow(articleIndex, '', '', ''));
        
        // Initialize Select2 for new row
        $(`.article-row[data-row="${articleIndex}"] .select2-article`).select2({
            theme: 'bootstrap-5'
        });
        
        articleIndex++;
        updateTotal();
    });

    // Initialize Select2 and update total on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for fournisseur
        $('#id_fournisseur').select2({
            theme: 'bootstrap-5',
            language: 'fr'
        });

        // Initialize Select2 for articles
        $('.select2-article').select2({
            theme: 'bootstrap-5'
        });

        // Update total
        updateTotal();
    });
</script>
@endpush
