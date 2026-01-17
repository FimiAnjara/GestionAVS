@extends('layouts.app')

@section('title', 'Modifier un Fournisseur')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="mb-4">
        <div>
            <h2><i class="bi bi-pencil-square"></i> Modifier le Fournisseur</h2>
            <p class="text-muted">Mettez à jour les informations</p>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-form-check"></i> Informations du Fournisseur
                    </h5>
                </div>
                <div class="card-body">
                    <form id="fournisseurForm" method="PUT" action="{{ route('fournisseurs.update', $fournisseur->id_fournisseur) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="id_fournisseur" class="form-label">
                                <i class="bi bi-hash"></i> ID Fournisseur
                            </label>
                            <input type="text" class="form-control" id="id_fournisseur" value="{{ $fournisseur->id_fournisseur }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="bi bi-building"></i> Nom du fournisseur <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ $fournisseur->nom }}" placeholder="Nom de l'entreprise" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lieux" class="form-label">
                                <i class="bi bi-geo-alt"></i> Lieu <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('lieux') is-invalid @enderror" 
                                   id="lieux" name="lieux" value="{{ $fournisseur->lieux }}" placeholder="Adresse ou localité" required>
                            @error('lieux')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning btn-lg me-2">
                                <i class="bi bi-check-circle"></i> Modifier
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
                        <i class="bi bi-info-circle"></i> Détails
                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Créé le:</strong></p>
                    <p class="text-muted">{{ $fournisseur->created_at->format('d/m/Y H:i') }}</p>
                    <hr>
                    <p><strong>Modifié le:</strong></p>
                    <p class="text-muted">{{ $fournisseur->updated_at->format('d/m/Y H:i') }}</p>
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
            const response = await fetch('{{ route("fournisseurs.update", $fournisseur->id_fournisseur) }}', {
                method: 'PUT',
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
                window.location.href = '{{ route("fournisseurs.list") }}';
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
