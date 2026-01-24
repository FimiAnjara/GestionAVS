@extends('layouts.app')

@section('title', 'Liste des Articles')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted">Consultez et gérez votre inventaire</p>
        </div>
        <a href="{{ route('articles.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-plus-circle me-2"></i> Ajouter un Article
        </a>
    </div>

    <!-- Filtres et Recherche -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Rechercher un article...">
            </div>
        </div>
        <form action="{{ route('articles.list') }}" method="GET" class="col-md-9 row g-3">
            <div class="col-md-3">
                <select name="categorie_id" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id_categorie }}" {{ request('categorie_id') == $cat->id_categorie ? 'selected' : '' }}>
                            {{ $cat->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="unite_id" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">Toutes les unités</option>
                    @foreach($unites as $un)
                        <option value="{{ $un->id_unite }}" {{ request('unite_id') == $un->id_unite ? 'selected' : '' }}>
                            {{ $un->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="entite_id" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">Toutes les entités</option>
                    @foreach($entites as $ent)
                        <option value="{{ $ent->id_entite }}" {{ request('entite_id') == $ent->id_entite ? 'selected' : '' }}>
                            {{ $ent->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="type_evaluation_id" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">Toutes les évaluations</option>
                    @foreach($typeEvaluations as $te)
                        <option value="{{ $te->id_type_evaluation_stock }}" {{ request('type_evaluation_id') == $te->id_type_evaluation_stock ? 'selected' : '' }}>
                            {{ $te->libelle }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <a href="{{ route('articles.list') }}" class="btn btn-outline-secondary w-100 shadow-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small text-uppercase">Photo</th>
                        <th class="py-3 text-muted small text-uppercase">ID</th>
                        <th class="py-3 text-muted small text-uppercase">Nom</th>
                        <th class="py-3 text-muted small text-uppercase">Catégorie</th>
                        <th class="py-3 text-muted small text-uppercase">Entité</th>
                        <th class="py-3 text-muted small text-uppercase">Évaluation</th>
                        <th class="py-3 text-muted small text-uppercase">Unité</th>
                        <th class="py-3 text-muted small text-uppercase text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="articlesTableBody">
                    @forelse($articles as $article)
                        <tr data-article-id="{{ $article->id_article }}">
                            <td class="px-4">
                                @if($article->photo)
                                    <img src="{{ asset('storage/' . $article->photo) }}" class="rounded shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><span class="text-muted small">#{{ $article->id_article }}</span></td>
                            <td class="fw-bold">{{ $article->nom }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ \App\Helpers\BadgeHelper::getCategoryColor($article->categorie->libelle ?? '') }}; color: white;">
                                    {{ $article->categorie->libelle ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($article->entite)
                                    <a href="{{ route('entite.show', $article->id_entite) }}" class="text-decoration-none">
                                        <span class="badge bg-secondary">
                                            {{ $article->entite->nom }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $article->typeEvaluation->libelle ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background-color: {{ \App\Helpers\BadgeHelper::getUnitColor($article->unite->libelle ?? '') }}; color: white;">
                                    {{ $article->unite->libelle ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('articles.show', $article->id_article) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('articles.edit', $article->id_article) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteConfirmModal"
                                    data-bs-url="{{ route('articles.destroy', $article->id_article) }}"
                                    data-bs-item="{{ $article->nom }}"
                                    title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">Aucun article trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $articles->links('pagination::bootstrap-4') }}
    </div>
</div>

<script>
    // Recherche en temps réel
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#articlesTableBody tr');
        
        rows.forEach(row => {
            const nom = row.querySelector('td:nth-child(3)');
            if (nom) {
                const isVisible = nom.textContent.toLowerCase().includes(searchValue);
                row.style.display = isVisible ? '' : 'none';
            }
        });
    });
</script>
@endsection
