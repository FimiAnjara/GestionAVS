@extends('layouts.app')

@section('title', 'Détails de l\'Article')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-box"></i> Détails de l'Article</h2>
            <p class="text-muted">Informations complètes</p>
        </div>
        <div>
            <a href="{{ route('articles.edit', $article->id_article) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('articles.list') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Détails -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box"></i> Informations Article
                    </h5>
                </div>
                <div class="card-body">
                    @if($article->photo)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $article->photo) }}" alt="Photo" style="max-height: 300px; max-width: 100%;">
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">ID Article</label>
                            <p class="fw-bold">{{ $article->id_article }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Nom</label>
                            <p class="fw-bold">{{ $article->nom }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Stock</label>
                            <p class="fw-bold"><span class="badge bg-warning text-dark">{{ $article->stock }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Catégorie</label>
                            <p class="fw-bold">
                                <span class="badge" style="background-color: {{ \App\Helpers\BadgeHelper::getCategoryColor($article->categorie->libelle ?? '') }}; color: white;">
                                    {{ $article->categorie->libelle ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Unité</label>
                            <p class="fw-bold">
                                <span class="badge" style="background-color: {{ \App\Helpers\BadgeHelper::getUnitColor($article->unite->libelle ?? '') }}; color: white;">
                                    {{ $article->unite->libelle ?? '-' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small">Créé le</label>
                            <p>{{ $article->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Modifié le</label>
                            <p>{{ $article->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte d'actions -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-exclamation-circle"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-danger w-100" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Supprimer cet article
                    </button>
                    <p class="small text-muted mt-3">
                        Cette action est irréversible. Soyez prudent.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('Êtes-vous absolument sûr de vouloir supprimer cet article ?')) {
            fetch('{{ route("articles.destroy", $article->id_article) }}', {
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
                    window.location.href = '{{ route("articles.list") }}';
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
