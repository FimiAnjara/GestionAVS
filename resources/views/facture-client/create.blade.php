@extends('layouts.app')

@section('title', 'Créer une Facture Client')

@section('header-buttons')
    <div class="d-flex gap-2">
        <a href="{{ route('facture-client.list') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>
@endsection

@section('content')
@section('content')
    @if($bonCommande)
    <!-- Formulaire de Création avec Bon de Commande -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-receipt me-2" style="color: #0056b3;"></i>
                Nouvelle Facture Client
            </h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('facture-client.store') }}" method="POST" id="factureForm">
                @csrf

                <div class="row mb-4">
                    <div class="col-lg-4">
                        <label for="date_" class="form-label">
                            <i class="bi bi-calendar me-2"></i>
                            Date de Facture
                        </label>
                        <input type="date" class="form-control @error('date_') is-invalid @enderror" id="date_"
                            name="date_" value="{{ old('date_', date('Y-m-d')) }}" required>
                        @error('date_')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label d-block text-muted small mb-1">Bon de Commande :</label>
                        <div class="bg-light p-2 rounded border">
                            <strong class="text-primary">{{ $bonCommande->id_bon_commande_client }}</strong><br>
                            <small class="text-muted">Proforma : {{ $bonCommande->id_proforma_client ?? 'N/A' }}</small>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label d-block text-muted small mb-1">Client :</label>
                        <div class="bg-light p-2 rounded border">
                            <strong>{{ $bonCommande->client->nom }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-12">
                        <label for="description" class="form-label">Description (optionnel)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="1" placeholder="Détails ou notes sur cette facture">{{ old('description', $bonCommande->description ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Articles -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="bi bi-basket me-2" style="color: #0056b3;"></i>
                        Articles Facturés
                    </h6>
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
                                @foreach($bonCommande->bonCommandeClientFille as $index => $item)
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
                                            <select class="form-select form-select-sm searchable-select article-select" name="articles[{{ $index }}][id_article]" required>
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
                            </tbody>
                            <tfoot>
                                <tr class="bg-light fw-bold">
                                    <td colspan="5" class="text-end">Montant Total :</td>
                                    <td class="text-end text-primary" id="grandTotalDisplay">
                                        {{ number_format($bonCommande->bonCommandeClientFille->sum(fn($i) => $i->quantite * $i->prix), 0, ',', ' ') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="btnAddArticle">
                        <i class="bi bi-plus-circle me-2"></i>Ajouter un article
                    </button>
                </div>

                <!-- Boutons -->
                <!-- Boutons -->
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body d-flex gap-3 justify-content-end">
                        <a href="{{ route('facture-client.list') }}" class="btn btn-secondary px-4">
                            <i class="bi bi-x-lg me-2"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>Enregistrer la Facture
                        </button>
                    </div>
                </div>

                <input type="hidden" name="id_bon_commande_client" value="{{ $bonCommande->id_bon_commande_client }}">
                <input type="hidden" name="id_client" value="{{ $bonCommande->id_client }}">
            </form>
        </div>
    </div>

    @else
    <!-- Sélection d'un Bon de Commande Client -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0 py-3">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>Sélectionner un Bon de Commande Client
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>Veuillez sélectionner un bon de commande validé
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>État</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\BonCommandeClient::where('etat', 11)->latest()->get() as $bc)
                        <tr>
                            <td><strong>{{ $bc->id_bon_commande_client }}</strong></td>
                            <td>{{ $bc->client->nom }}</td>
                            <td>{{ $bc->date_->format('d/m/Y') }}</td>
                            <td><span class="badge bg-success">Validé</span></td>
                            <td class="text-center">
                                <a href="{{ route('facture-client.create', ['bon_commande_id' => $bc->id_bon_commande_client]) }}" 
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

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

<script>
$(document).ready(function() {
    const articlesJS = @json($articlesJS);
    const bonCommande = @json($bonCommande);
    
    // In Facture create, id_magasin is usually implicit from BC but not explicitly selectable in the form shown.
    // However, the user request says "in each entry page". 
    // If id_magasin is not a select in this specific view but comes from BC, we filter based on BC's magasin.
    
    initSelect2();
    let articleCount = {{ isset($bonCommande) ? $bonCommande->bonCommandeClientFille->count() : 0 }};

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

    // Initial filter if BC is present
    if (bonCommande && bonCommande.magasin && bonCommande.magasin.site) {
        const entiteId = bonCommande.magasin.site.id_entite;
        $('.article-select').each(function() {
            filterArticleDropdown($(this), entiteId);
        });
    }

    $('#btnAddArticle').on('click', function() {
        const container = document.getElementById('articlesContainer');
        const entiteId = (bonCommande && bonCommande.magasin && bonCommande.magasin.site) ? bonCommande.magasin.site.id_entite : null;

        const html = `
            <tr class="article-row">
                <td class="text-center article-photo-container">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>
                </td>
                <td>
                    <select class="form-select form-select-sm searchable-select article-select" name="articles[${articleCount}][id_article]" required>
                        <option value="">-- Sélectionner --</option>
                    </select>
                </td>
                <td class="text-center article-unite text-muted small">-</td>
                <td><input type="number" class="form-control form-control-sm quantite-input" name="articles[${articleCount}][quantite]" placeholder="0" min="0.01" step="0.01" required></td>
                <td><input type="number" class="form-control form-control-sm prix-input" name="articles[${articleCount}][prix]" placeholder="0" min="0" step="0.01" required></td>
                <td class="text-end fw-bold text-primary"><span class="row-total">0</span></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger btn-remove-article"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;
        container.insertAdjacentHTML('beforeend', html);
        
        const newSelect = $(container).find('.article-select:last');
        filterArticleDropdown(newSelect, entiteId);
        
        initSelect2(newSelect);
        articleCount++;
        updateRemoveButtons();
    });

    $(document).on('click', '.btn-remove-article', function() { $(this).closest('.article-row').remove(); updateRemoveButtons(); calculateGrandTotal(); });
    $(document).on('change', '.article-select', function() {
        const selected = $(this).find(':selected');
        const row = $(this).closest('tr');
        row.find('.article-unite').text(selected.data('unite') || '-');
        const photo = selected.data('photo');
        row.find('.article-photo-container').html(photo ? `<img src="${photo}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">` : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`);
    });
    $(document).on('input', '.quantite-input, .prix-input', function() {
        const row = $(this).closest('tr');
        const qte = parseFloat(row.find('.quantite-input').val()) || 0;
        const prix = parseFloat(row.find('.prix-input').val()) || 0;
        row.find('.row-total').text((qte * prix).toLocaleString('fr-FR'));
        calculateGrandTotal();
    });

    function calculateGrandTotal() {
        let total = 0;
        $('.article-row').each(function() { total += (parseFloat($(this).find('.quantite-input').val()) || 0) * (parseFloat($(this).find('.prix-input').val()) || 0); });
        $('#grandTotalDisplay').text(total.toLocaleString('fr-FR'));
    }
    function updateRemoveButtons() { $('.btn-remove-article').toggle($('.article-row').length > 1); }
    function initSelect2(element = null) { (element || $('.searchable-select')).select2({ language: 'fr', width: '100%', placeholder: '-- Sélectionner --' }); }
});
</script>
@endsection
