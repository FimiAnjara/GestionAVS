@extends('layouts.app')

@section('title', 'Modifier un Article')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <div>
            <p class="text-muted">Mettez à jour les informations</p>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-form-check"></i> Informations de l'Article
                    </h5>
                </div>
                <div class="card-body">
                    <form id="articleForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="id_article" class="form-label">
                                <i class="bi bi-hash"></i> ID Article
                            </label>
                            <input type="text" class="form-control" id="id_article" value="{{ $article->id_article }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="bi bi-box"></i> Nom de l'article <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ $article->nom }}" placeholder="Nom du produit" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="id_categorie" class="form-label">
                                <i class="bi bi-tag"></i> Catégorie <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('id_categorie') is-invalid @enderror" 
                                    id="id_categorie" name="id_categorie" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_categorie }}" {{ $cat->id_categorie == $article->id_categorie ? 'selected' : '' }}>
                                        {{ $cat->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_categorie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_unite" class="form-label">
                                    <i class="bi bi-rulers"></i> Unité <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('id_unite') is-invalid @enderror" 
                                        id="id_unite" name="id_unite" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($unites as $unite)
                                        <option value="{{ $unite->id_unite }}" {{ $unite->id_unite == $article->id_unite ? 'selected' : '' }}>
                                            {{ $unite->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_unite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_entite" class="form-label">
                                    <i class="bi bi-building"></i> Entité <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('id_entite') is-invalid @enderror" 
                                        id="id_entite" name="id_entite" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($entites as $entite)
                                        <option value="{{ $entite->id_entite }}" {{ $entite->id_entite == $article->id_entite ? 'selected' : '' }}>
                                            {{ $entite->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_entite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_type_evaluation_stock" class="form-label">
                                    <i class="bi bi-calculator"></i> Type d'Évaluation <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('id_type_evaluation_stock') is-invalid @enderror" 
                                        id="id_type_evaluation_stock" name="id_type_evaluation_stock" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($typeEvaluations as $type)
                                        <option value="{{ $type->id_type_evaluation_stock }}" {{ $type->id_type_evaluation_stock == $article->id_type_evaluation_stock ? 'selected' : '' }}>
                                            {{ $type->libelle }} ({{ $type->id_type_evaluation_stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_type_evaluation_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="prix_vente" class="form-label">
                                    <i class="bi bi-currency-exchange"></i> Prix de Vente (Ar) <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('prix_vente') is-invalid @enderror" 
                                       id="prix_vente" name="prix_vente" 
                                       value="{{ $article->articleFille->first()?->prix ?? 0 }}" 
                                       placeholder="0.00" min="0" step="0.01" required>
                                <small class="text-muted">Prix unitaire de vente</small>
                                @error('prix_vente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">
                                    <i class="bi bi-image"></i> Photo
                                </label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <small class="text-muted">JPG, PNG, GIF (Max: 2MB)</small>
                                @if($article->photo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $article->photo) }}" alt="Photo" style="height: 60px; width: auto; border-radius: 4px;">
                                    </div>
                                @endif
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning btn-lg me-2">
                                <i class="bi bi-check-circle"></i> Modifier
                            </button>
                            <a href="{{ route('articles.list') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations utiles -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Détails
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Créé le:</strong></p>
                    <p class="text-muted">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                    <hr>
                    <p><strong>Modifié le:</strong></p>
                    <p class="text-muted">{{ $article->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('articleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("articles.update", $article->id_article) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message);
                window.location.href = '{{ route("articles.list") }}';
            } else {
                alert('Erreur: ' + result.message);
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        }
    });
</script>
@endsection
