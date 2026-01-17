@extends('layouts.app')

@section('title', 'Ajouter un Article')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <div>
            <h2><i class="bi bi-plus-circle"></i> Ajouter un nouvel Article</h2>
            <p class="text-muted">Remplissez le formulaire ci-dessous</p>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-form-check"></i> Informations de l'Article
                    </h5>
                </div>
                <div class="card-body">
                    <form id="articleForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="bi bi-box"></i> Nom de l'article <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Nom du produit" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">
                                    <i class="bi bi-box-seam"></i> Stock <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" placeholder="0" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_categorie" class="form-label">
                                    <i class="bi bi-tag"></i> Catégorie <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('id_categorie') is-invalid @enderror" 
                                        id="id_categorie" name="id_categorie" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id_categorie }}">{{ $cat->libelle }}</option>
                                    @endforeach
                                </select>
                                @error('id_categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
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
                                        <option value="{{ $unite->id_unite }}">{{ $unite->libelle }}</option>
                                    @endforeach
                                </select>
                                @error('id_unite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">
                                    <i class="bi bi-image"></i> Photo
                                </label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <small class="text-muted">JPG, PNG, GIF (Max: 2MB)</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg me-2">
                                <i class="bi bi-check-circle"></i> Ajouter
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
                        <i class="bi bi-info-circle"></i> Aide
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        <strong>Champs obligatoires:</strong>
                    </p>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success"></i> Nom</li>
                        <li><i class="bi bi-check-circle text-success"></i> Stock</li>
                        <li><i class="bi bi-check-circle text-success"></i> Catégorie</li>
                        <li><i class="bi bi-check-circle text-success"></i> Unité</li>
                    </ul>
                    <hr>
                    <p class="small text-muted">
                        La photo est optionnelle. Un identifiant unique sera généré automatiquement.
                    </p>
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
            const response = await fetch('{{ route("articles.store") }}', {
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
