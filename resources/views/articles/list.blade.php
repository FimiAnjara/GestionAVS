@extends('layouts.app')

@section('title', 'Liste des Articles')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-box"></i> Gestion des Articles</h2>
            <p class="text-muted">Consultez et gérez tous vos articles</p>
        </div>
        <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Ajouter Article
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-primary text-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom...">
            </div>
        </div>
    </div>

    <!-- Tableau des articles -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4"><i class="bi bi-hash"></i> ID</th>
                        <th><i class="bi bi-image"></i> Photo</th>
                        <th><i class="bi bi-box"></i> Nom</th>
                        <th><i class="bi bi-box-seam"></i> Stock</th>
                        <th><i class="bi bi-tag"></i> Catégorie</th>
                        <th><i class="bi bi-rulers"></i> Unité</th>
                        <th><i class="bi bi-calendar"></i> Date</th>
                        <th class="text-center"><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody id="articlesTableBody">
                    @forelse($articles as $article)
                        <tr data-article-id="{{ $article->id_article }}">
                            <td class="ps-4">
                                <span class="badge bg-info text-dark" style="word-break: break-all;">{{ $article->id_article }}</span>
                            </td>
                            <td>
                                @if($article->photo)
                                    <img src="{{ asset('storage/' . $article->photo) }}" alt="Photo" style="height: 40px; width: 40px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <span class="text-muted"><i class="bi bi-image"></i></span>
                                @endif
                            </td>
                            <td><strong class="text-dark">{{ $article->nom }}</strong></td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ $article->stock }}</span>
                            </td>
                            <td>{{ $article->categorie->libelle ?? '-' }}</td>
                            <td>{{ $article->unite->libelle ?? '-' }}</td>
                            <td>
                                <small class="text-muted">{{ $article->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('articles.show', $article->id_article) }}" class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('articles.edit', $article->id_article) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteArticle('{{ $article->id_article }}')" title="Supprimer">
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

    function deleteArticle(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
            fetch('{{ route("articles.destroy", ":id") }}'.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            });
        }
    }
</script>
@endsection
