@extends('layouts.app')

@section('title', 'Ajouter un Fournisseur')

@section('content')
    <div class="container-fluid py-4">
        <!-- En-tête -->
        <div class="mb-4">
            <div>
                <h2><i class="bi bi-plus-circle"></i> Ajouter un nouveau Fournisseur</h2>
                <p class="text-muted">Remplissez le formulaire ci-dessous</p>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-form-check"></i> Informations du Fournisseur
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="fournisseurForm" method="POST" action="{{ route('fournisseurs.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="nom" class="form-label">
                                    <i class="bi bi-briefcase"></i> Nom du fournisseur <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom"
                                    name="nom" placeholder="Nom de l'entreprise" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="lieux" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Lieu <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('lieux') is-invalid @enderror"
                                    id="lieux" name="lieux" placeholder="Adresse ou localité" required>
                                @error('lieux')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg me-2">
                                    <i class="bi bi-check-circle"></i> Ajouter
                                </button>
                                <a href="{{ route('fournisseurs.list') }}" class="btn btn-secondary btn-lg">
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
                            <li><i class="bi bi-check-circle text-success"></i> Nom du fournisseur</li>
                            <li><i class="bi bi-check-circle text-success"></i> Lieu</li>
                        </ul>
                        <hr>
                        <p class="small text-muted">
                            Un identifiant unique sera généré automatiquement pour ce nouveau fournisseur.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('fournisseurForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                nom: formData.get('nom'),
                lieux: formData.get('lieux')
            };

            try {
                const response = await fetch('{{ route('fournisseurs.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    window.location.href = '{{ route('fournisseurs.list') }}';
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
